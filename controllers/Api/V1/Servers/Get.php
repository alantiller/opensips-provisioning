<?php

/*
|--------------------------------------------------------------------------
| Servers Get Controller
|--------------------------------------------------------------------------
*/

namespace Api\V1\Servers;

use \Exception;

class Get
{
    public static function get()
    {
        global $local;

        try {
            // Check authentication before continuining
            self::authenticate();

            // Create the new subscriber in the server
            $servers = $local->select('osp_servers', '*');

            // Create the response
            $response = array('data' => $servers);

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
        $permission = \Auth\Permissions::canRead('osp_servers', $user['id']);
        if (!$permission)
        {
            throw new Exception("Your account is not authorised to read servers, please ask an admin to give you access.", 403);    
        }

        return true;
    }
}
