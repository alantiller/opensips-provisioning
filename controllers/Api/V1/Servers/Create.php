<?php

/*
|--------------------------------------------------------------------------
| Customisation Controller
|--------------------------------------------------------------------------
*/

namespace Api\V1\Servers;

use \Exception;
use \Tokenly\TokenGenerator\TokenGenerator;

class Create
{
    public static function create()
    {
        global $local;

        try {
            // Check authentication before continuining
            self::authenticate();

            // This gets the data from the API and validates its correct and there are no conflicts before proceeding
            $output = self::validate();

            // Create the account
            $local->insert("osp_servers", [
                "description" => $output['description'],
                "address" => $output['address'],
                "default_domain" => $output['default_domain'],
                "default_attribute" => $output['default_attribute'],
                "default_type" => $output['default_type'],
                "enabled" => $output['enabled'],
                "timestamp" => date("Y-m-d H:i:s")
            ]);

            // Return response
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array("data" => array('code' => 200, 'message' => 'User created.')));
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
        $permission = \Auth\Permissions::canCreate('osp_servers', $user['id']);
        if (!$permission)
        {
            throw new Exception("You account is not authorised to use this endpoint, please ask an admin to give you access.", 403);    
        }

        return true;
    }

    // This function gets and validates the data passed through the API before letting the create process continue
    private static function validate()
    {
        global $local;

        // Get the body
        $body = file_get_contents('php://input'); 

        // If body exists run query
        if (!$body) 
        {
            throw new Exception("A POST request requires a body to be passed through.", 400);
        }

        // Check the content type header is application/json or */*
        if (!isset($_SERVER['HTTP_CONTENT_TYPE']) || $_SERVER['HTTP_CONTENT_TYPE'] != 'application/json')
        {
            throw new Exception("The content type needs to be application/json.", 400);
        }

        // Parse the JSON body
        $json = json_decode($body, true);
                
        // Check the description was passed
        if (!isset($json['description']))
        {
            throw new Exception("The description was missing from the request.", 400);
        }

        // Check the address was passed
        if (!isset($json['address']))
        {
            throw new Exception("The address was missing from the request.", 400);
        }

        // Check the default_domain was passed
        if (!isset($json['default_domain']))
        {
            throw new Exception("The default_domain was missing from the request.", 400);
        }

        // Check the default_attribute was passed
        if (!isset($json['default_attribute']))
        {
            throw new Exception("The default_attribute was missing from the request.", 400);
        }

        // Check the default_type was passed
        if (!isset($json['default_type']))
        {
            throw new Exception("The default_type was missing from the request.", 400);
        }

        // Check the enabled field was passed
        if (!isset($json['enabled']))
        {
            throw new Exception("The enabled field was missing from the request.", 400);
        }

        // Check the subscriber does not already exist
        $user_count = $local->count('osp_servers', ['address' => $json['address']]);
        if ($user_count > 0)
        {
            throw new Exception("This server already exists.", 409);
        }

        // Return the required values as an array
        return array('description' => $json['description'], 'address' => $json['address'], 'default_domain' => $json['default_domain'], 'default_attribute' => $json['default_attribute'], 'default_type' => $json['default_type'], 'enabled' => $json['enabled']);
    }
}
