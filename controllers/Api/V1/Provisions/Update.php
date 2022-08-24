<?php

/*
|--------------------------------------------------------------------------
| Provisions Controller
|--------------------------------------------------------------------------
*/

namespace Api\V1\Provisions;

use \Exception;

class Update
{
    public static function update($id)
    {
        global $local;

        try {
            // Check authentication before continuining
            self::authenticate();

            // Check the subscriber exists
            $count = $local->count('osp_provisions', ['id' => $id]);
            if ($count != 1)
            {
                throw new Exception("The provision does not exist.", 404);
            }

            // Validate the data being passed
            $body = self::validate();

            // Create the new subscriber in the server
            $local->update('osp_provisions', $body, ['id' => $id]);

            // Return response
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array("data" => array('code' => 200, 'message' => 'Provision updated.')));
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
        $permission = \Auth\Permissions::canWrite('osp_provisions', $user['id']);
        if (!$permission)
        {
            throw new \Exception("Your account is not authorised to update provisions, please ask an admin to give you access.", 403);    
        }

        return true;
    }

    // This function gets and validates the data passed through the API before letting the create process continue
    private static function validate()
    {
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
        
        // Return the decoded body
        return $json;
    }
}