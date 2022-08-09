<?php

/*
|--------------------------------------------------------------------------
| Customisation Controller
|--------------------------------------------------------------------------
*/

namespace Api\V1\Permissions;

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

            // Hash the users password
            $calculated_value = intval($output['read']) + intval($output['update']) + intval($output['create']) + intval($output['delete']);

            // Create the account
            $local->insert("osp_permissions", [
                "group" => $output['group'],
                "entity" => $output['entity'],
                "value" => $calculated_value,
                "timestamp" => date("Y-m-d H:i:s")
            ]);

            // Return response
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array("data" => array('code' => 200, 'message' => 'Permission created.')));
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
        $permission = \Auth\Permissions::canCreate('osp_permissions', $user['id']);
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
                
        // Check the group was passed
        if (!isset($json['group']))
        {
            throw new Exception("The group was missing from the request.", 400);
        }

        // Check the entity was passed
        if (!isset($json['entity']))
        {
            throw new Exception("The entity was missing from the request.", 400);
        }

        // Check the read was passed
        if (!isset($json['read']))
        {
            throw new Exception("The read was missing from the request.", 400);
        }

        // Check the update was passed
        if (!isset($json['update']))
        {
            throw new Exception("The update was missing from the request.", 400);
        }

        // Check the create was passed
        if (!isset($json['create']))
        {
            throw new Exception("The create was missing from the request.", 400);
        }

        // Check the delete was passed
        if (!isset($json['delete']))
        {
            throw new Exception("The delete was missing from the request.", 400);
        }

        // Check the subscriber does not already exist
        $user_count = $local->count('osp_users', ['group' => $json['group'], 'entity' => $json['entity']]);
        if ($user_count > 0)
        {
            throw new Exception("That permission already exists.", 409);
        }

        // Return the required values as an array
        return array('group' => $json['group'], 'entity' => $json['entity'], 'read' => $json['read'], 'update' => $json['update'], 'create' => $json['create'], 'delete' => $json['delete']);
    }
}
