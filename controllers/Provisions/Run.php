<?php

/*
|--------------------------------------------------------------------------
| Customisation Controller
|--------------------------------------------------------------------------
*/

namespace Provisions;

use \Exception;
use \Tokenly\TokenGenerator\TokenGenerator;
use \GuzzleHttp\Client;

class Run
{
    private $provision_id;
    private $subscriber_id;
    private $cli;
    private $password;

    public function __construct($provision_id, $subscriber_id, $cli, $password)
    {
        $this->provision_id = $provision_id;
        $this->subscriber_id = $subscriber_id;
        $this->cli = $cli;
        $this->password = $password;

        return $this->run();
    }

    private function run()
    {
        global $local;

        // Get all server from the gateways table
        $provision = $local->get('osp_provisions', '*', ['id' => $this->provision_id]);

        try {
            // Create the body
            $request_body = $provision['request_body'];
            $request_body = str_replace('{{OpenSIPS.Username}}', $this->cli, $request_body);
            $request_body = str_replace('{{OpenSIPS.Password}}', $this->password, $request_body);

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
                    case 'api-key':
                        $options['headers'] = array($auth_json['key'] => $auth_json['value']);
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
                    $this->execute($provision['on_success'], $response->getBody());
                }
            }
            else
            {
                if ($provision['on_failure'] != null || $provision['on_failure'] != "")
                {
                    $this->execute($provision['on_failure'], $response->getBody());
                }
            }

            return true;
        } catch (Exception $error) {
            return false;
        }
    }

    private function execute($code, $response_body)
    {
        try {
            eval($code);
        } catch (Exception $error) {
            return false;
        }
    }

    private function metadata($key, $value)
    {
        global $local;

        try {
            // Check the subscriber exists
            $metadata = $local->select('osp_metadata', '*', ['subscriber' => intval($this->subscriber_id), 'name' => $key]);
            if (count($metadata) == 0) {
                $local->insert('osp_metadata', ['subscriber' => intval($this->subscriber_id), 'name' => $key, 'value' => addslashes($value), 'timestamp' => date("Y-m-d H:i:s")]);
            } else {
                $metadata = $metadata[0];
                $local->update('osp_metadata', ['value' => addslashes($value), 'timestamp' => date("Y-m-d H:i:s")], ['id' => $metadata['id']]);
            }
        } catch (Exception $error) {
            return false;
        }
    }
}
