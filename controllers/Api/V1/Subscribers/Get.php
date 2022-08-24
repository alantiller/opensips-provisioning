<?php

/*
|--------------------------------------------------------------------------
| Subscriber Get Controller
|--------------------------------------------------------------------------
*/

namespace Api\V1\Subscribers;

use \Exception;

class Get
{
    public static function get($id)
    {
        global $opensips, $local;

        try {
            // Check authentication before continuining
            self::authenticate();

            // Check the subscriber exists
            $count = $opensips->count('usr_preferences', ['id' => $id]);
            if ($count != 1)
            {
                throw new Exception("The subscriber does not exist.", 404);
            }

            // Create the new subscriber in the server
            $subscriber = $opensips->get('usr_preferences', '*', ['id' => $id]);

            // Create the response
            $response = array('data' => $subscriber);

            // Create metadata array
            $response['data']['metadata'] = array();

            // Get the subscriber metadata
            $ops_metadata = $local->select('osp_metadata', '*', ['subscriber' => $subscriber['id']]);
            foreach ($ops_metadata as $ops_md) {
                $logic_name = $ops_md['name'];
                $response['data']['metadata'][$logic_name] = $ops_md['value'];
            }

            // Return response
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($response);
        } 
        catch (Exception $error) 
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
            throw new Exception("You are not authorised to access this endpoint, please provide an API Key.", 403);    
        }
        $user = \Auth\Auth::get($token);

        // Check the user has permission
        $permission = \Auth\Permissions::canRead('usr_preferences', $user['id']);
        if (!$permission)
        {
            throw new Exception("Your account is not authorised to delete subscribers, please ask an admin to give you access.", 403);    
        }

        return true;
    }
}
