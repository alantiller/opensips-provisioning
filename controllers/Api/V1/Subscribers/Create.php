<?php

/*
|--------------------------------------------------------------------------
| Customisation Controller
|--------------------------------------------------------------------------
*/

namespace Api\V1\Subscribers;

use \Exception;
use \Tokenly\TokenGenerator\TokenGenerator;
use \GuzzleHttp\Client;

class Create
{
    public static function create()
    {
        global $opensips;

        try {
            // Check authentication before continuining
            self::authenticate();

            // This gets the data from the API and validates its correct and there are no conflicts before proceeding
            $output = self::validate();

            // Select the server with the lowest
            $server = self::calculate();

            // Work out values
            $domain = ($output['domain'] != null ? $output['domain'] : $server['domain']);
            $attribute = ($output['attribute'] != null ? $output['attribute'] : $server['attribute']);
            $type = ($output['type'] != null ? $output['type'] : $server['type']);

            // Run a provisions that have been setup by the end user
            self::provision($server['address'], $output['cli']);

            // Create the new subscriber in the server
            $opensips->insert('usr_preferences', [
                'uuid' => $output['cli'],
                'username' => $output['cli'],
                'domain' => $domain,
                'attribute' => $attribute,
                'type' => $type,
                'value' => $server['address'],
                'last_modified' => \Medoo\Medoo::raw('NOW()')
            ]);

            // Return response
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array("data" => array('code' => 200, 'message' => 'Subscriber created.')));
        } 
        catch (\Exception $error) 
        {
            // Return the exception
            http_response_code($error->getCode());
            header('Content-Type: application/json');
            echo json_encode(array("error" => array("code" => $error->getCode(), "message" => $error->getMessage())));
        }
    }
    
    // This function authenticates the API with the API key and errors if the API key is wrong
    private static function authenticate()
    {
        // Check if Token was passed in request
        $token = (isset($_SERVER['HTTP_AUTHORIZATION']) ? str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']) : null);

        // Check the authentication is correct
        $check = \Auth\Auth::check($token);
        if (!$check)
        {
            throw new \Exception("You are not authorised to access this endpoint, please provide an API Key.", 403);    
        }
        $user = \Auth\Auth::get($token);

        // Check the user has permission
        $permission = \Auth\Permissions::canCreate('usr_preferences', $user['id']);
        if (!$permission)
        {
            throw new \Exception("Your account is not authorised to create subscribers, please ask an admin to give you access.", 403);    
        }

        return true;
    }

    // This function gets and validates the data passed through the API before letting the create process continue
    private static function validate()
    {
        global $opensips;

        // Get the body
        $body = file_get_contents('php://input'); 

        // If body exists run query
        if (!$body) 
        {
            throw new \Exception("A POST request requires a body to be passed through.", 400);
        }

        // Check the content type header is application/json or */*
        if (!isset($_SERVER['HTTP_CONTENT_TYPE']) || $_SERVER['HTTP_CONTENT_TYPE'] != 'application/json')
        {
            throw new \Exception("The content type needs to be application/json.", 400);
        }

        // Parse the JSON body
        $json = json_decode($body, true);
                
        // Check the CLI was passed and is 11 digits
        if (!isset($json['cli']))
        {
            throw new \Exception("The CLI was missing from the request.", 400);
        }

        // Check the CLI was passed and is 11 digits
        if (strlen($json['cli']) != 11)
        {
            throw new \Exception("The CLI must be 11 digits long.", 400);
        }

        // Check the CLI is only numbers
        if (!is_numeric($json['cli']))
        {
            throw new \Exception("Please make sure the CLI includes only numbers.", 400);
        }

        // Check the subscriber does not already exist
        $subscriber_count = $opensips->count('usr_preferences', ['username' => $json['cli']]);
        if ($subscriber_count > 0)
        {
            throw new \Exception("This subscriber already exists.", 409);
        }

        // Work out values
        $domain = (isset($json['domain']) ? $json['domain'] : null);
        $attribute = (isset($json['attribute']) ? $json['attribute'] : null);
        $type = (isset($json['type']) ? $json['type'] : null);

        // Return the required values as an array
        return array('cli' => $json['cli'], 'domain' => $domain, 'attribute' => $attribute, 'type' => $type);
    }

    // This function is used to calculate the server with the lowest number of customers and selects it for the provision
    private static function calculate()
    {
        global $opensips, $local;

        // Get all server from the gateways table
        $servers = $local->select('osp_servers', ['id', 'address', 'default_domain', 'default_attribute', 'default_type', 'description'], ['enabled' => 1]);

        // Create a table to put results in
        $results = array();

        // Loop through each gateway and get a count
        foreach ($servers as $server) {
            $count = $opensips->get('usr_preferences', ['subscribers' => \Medoo\Medoo::raw('COUNT(*)')], ['value' => $server['address']]);
            array_push($results, array('address' => $server['address'], 'domain' => $server['default_domain'], 'attribute' => $server['default_attribute'], 'type' => $server['default_type'], 'subscribers' => $count));
        }

        usort($results, function($a, $b) {
            return $a['subscribers'] <=> $b['subscribers'];
        });
        
        return $results[0];
    }

    // This function is called when a create command is run and triggers any provisions setup in OpenSIPS Provisioner
    private static function provision($server, $cli)
    {
        global $local;

        // Generate global password
        $password = (new TokenGenerator())->generateToken(12);

        // Get all server from the gateways table
        $provisions = $local->select('osp_provisions', '*', ['server' => $server, 'enabled' => 1]);

        // Loop through each gateway and get a count
        foreach ($provisions as $provision) {
            try {
                // Create the body
                $request_body = $provision['request_body'];
                $request_body = str_replace('{{OpenSIPS.Username}}', $cli, $request_body);
                $request_body = str_replace('{{OpenSIPS.Password}}', $password, $request_body);

                // Create options
                $options = array('body' => $request_body);

                // Set auth
                if (isset($provision['request_auth']) && $provision['request_auth'] != null)
                {
                    $auth_json = json_decode($provision['request_auth'], true);
                    switch ($auth_json['type']) {
                        case 'basic':
                            $options['auth'] = array($auth_json['username'], $auth_json['password']);
                            break;
                        case 'bearer':
                            $options['headers'] = array('Authorization' => 'Bearer ' . $auth_json['bearer']);
                            break;
                    }
                }

                // Make the API call
                $client = new Client();
                $response = $client->request($provision['request_method'], $provision['request_url'], $options);

                // Get body and execute any code
                if (substr(strval($response->getStatusCode()), 0, 1) === "2")
                {
                    if ($provision['on_success'] != null || $provision['on_success'] != "")
                    {
                        self::execute($provision['on_success'], $response->getBody());
                    }
                }
                else
                {
                    if ($provision['on_failure'] != null || $provision['on_failure'] != "")
                    {
                        self::execute($provision['on_failure'], $response->getBody());
                    }
                }
            } catch (Exception $error) {
            }
        }
        return true;
    }

    private static function execute($code)
    {
        try {
            eval($code);
        } catch (Exception $error) {
            return false;
        }
    }
}
