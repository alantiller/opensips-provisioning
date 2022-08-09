<?php

/*
|--------------------------------------------------------------------------
| Dashboard Subscribers Controller
|--------------------------------------------------------------------------
*/

namespace Api\V1\Dashboard;

class Subscribers
{
    public static function total()
    {
        global $opensips;

        try {
            // Check authentication before continuining
            self::authenticate();

            // Get all server from the gateways table
            $count = $opensips->count('usr_preferences');

            // Create response
            $response = array('data' => array('count' => $count));

            // Respond with the chart data
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($response);
        } 
        catch (\Exception $error) 
        {
            // Return the exception
            http_response_code($error->getCode());
            header('Content-Type: application/json');
            echo json_encode(array("error" => array("code" => $error->getCode(), "message" => $error->getMessage())));
        }
    }

    public static function all()
    {
        global $opensips, $local;

        try {
            // Check authentication before continuining
            self::authenticate();

            // Create response
            $response = array('data' => array());

            // Get all server from the gateways table
            $servers = $local->select('osp_servers', ['id', 'address', 'description'], ['enabled' => 1]);

            // Loop through each gateway and get a count
            foreach ($servers as $server) {
                $count = $opensips->get('usr_preferences', ['subscribers' => \Medoo\Medoo::raw('COUNT(*)')], ['value' => $server['address']]);
                array_push($response['data'], array('server' => $server['description'], 'subscribers' => intval($count['subscribers'])));
            }

            // Respond with the chart data
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($response);
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
        global $local;

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
        $permission = \Auth\Permissions::canRead('usr_preferences', $user['id']);
        if (!$permission)
        {
            throw new \Exception("You account is not authorised to use this endpoint, please ask an admin to give you access.", 403);    
        }

        return true;
    }
}
