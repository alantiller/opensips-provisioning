<?php

/*
|--------------------------------------------------------------------------
| Auth Login Controller
|--------------------------------------------------------------------------
*/

namespace Api\V1;

class Auth
{
    public static function authenticate()
    {
        global $local;

        try {
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

            \Auth\Auth::authenticate($json['username'], $json['password']);

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

    public static function destroy()
    {
        try {
            \Auth\Auth::destroy();

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
}
