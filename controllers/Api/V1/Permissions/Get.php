<?php

/*
|--------------------------------------------------------------------------
| Customisation Controller
|--------------------------------------------------------------------------
*/

namespace Api\V1\Permissions;

use \Exception;
use \Tokenly\TokenGenerator\TokenGenerator;

class Get
{
    public static function groups()
    {
        global $local;

        try {
            // Check authentication before continuining
            self::authenticate();

            // Create response
            $response = array("data" => array());

            // Get all server from the gateways table
            $groups = $local->select("osp_permissions", ["@group"]);

            // Loop through each gateway and get a count
            foreach ($groups as $group) {
                array_push($response['data'], $group['group']);
            }

            // Return response
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
        $permission = \Auth\Permissions::canRead('osp_users', $user['id']);
        if (!$permission)
        {
            throw new Exception("You account is not authorised to use this endpoint, please ask an admin to give you access.", 403);    
        }

        return true;
    }
}
