<?php

/*
|--------------------------------------------------------------------------
| Account Password Controller
|--------------------------------------------------------------------------
*/

namespace Api\V1\Account;

use \Exception;
use \Tokenly\TokenGenerator\TokenGenerator;

class Password
{
    public static function change()
    {
        global $local;

        try {
            // Check authentication before continuining
            $user = self::authenticate();

            // This gets the data from the API and validates its correct and there are no conflicts before proceeding
            $output = self::validate();

            // Check the current password is correct
            $current_salted_hash = hash('sha256', $output['password'] . $user['salt']);
            if ($user['password'] != $current_salted_hash) {
                throw new Exception('The entered current password was incorrect.', 400);
            }

            // Generate the new password
            $password_salt = (new TokenGenerator())->generateToken(20);
            $salted_hash = hash('sha256', $output['new_password'] . $password_salt);

            // Create the account
            $local->update("osp_users", [
                "password" => $salted_hash,
                "salt" => $password_salt
            ], ['id' => $user['id']]);
            
            // Respond with 204
            http_response_code(204);
            header('Content-Type: application/json');
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

        return $user;
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
                
        // Check the password was passed
        if (!isset($json['password']))
        {
            throw new Exception("The password field was missing from the request.", 400);
        }

        // Check the new password was passed
        if (!isset($json['new_password']))
        {
            throw new Exception("The new password field was missing from the request.", 400);
        }

        // Return the required values as an array
        return array('password' => $json['password'], 'new_password' => $json['new_password']);
    }
}
