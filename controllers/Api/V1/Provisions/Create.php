<?php

/*
|--------------------------------------------------------------------------
| Provisions Controller
|--------------------------------------------------------------------------
*/

namespace Api\V1\Provisions;

use \Exception;
use \Tokenly\TokenGenerator\TokenGenerator;
use \GuzzleHttp\Client;

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

            // Create the new subscriber in the server
            $local->insert('osp_provisions', [
                'server' => $output['server'],
                'opperation' => $output['opperation'],
                'description' => $output['description'],
                'timestamp' => date("Y-m-d H:i:s")
            ]);

            // Return response
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array("data" => array('code' => 200, 'message' => 'Provision created.')));
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
        $permission = \Auth\Permissions::canCreate('osp_provisions', $user['id']);
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
                
        // Check the server was passed
        if (!isset($json['server']))
        {
            throw new \Exception("The server was missing from the request.", 400);
        }

        // Check the opperation was passed
        if (!isset($json['opperation']))
        {
            throw new \Exception("The opperation was missing from the request.", 400);
        }

        // Check the description was passed
        if (!isset($json['description']))
        {
            throw new \Exception("The description was missing from the request.", 400);
        }

        // Return the required values as an array
        return array('server' => $json['server'], 'opperation' => $json['opperation'], 'description' => $json['description']);
    }
}