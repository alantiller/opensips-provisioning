<?php

/*
|--------------------------------------------------------------------------
| Auth Login Controller
|--------------------------------------------------------------------------
*/

namespace Audit;

use \Exception;

class Audit
{
    public static function log()
    {
        global $local;

        try {
            // Checl the request method isn't GET
            if ($_SERVER['REQUEST_METHOD'] === 'GET')
            {
                throw new Exception("The system is not logging GET requests");
            }

            // Get the request body
            $request_body = file_get_contents('php://input');

            // Remove sensative fields
            $request_body = self::omitted($request_body);

            // Get the user
            $user = self::accountability();

            // Create the audit log
            $local->insert('osp_audit', [
                'method' => $_SERVER['REQUEST_METHOD'],
                'user' => $user,
                'request_url' => $_SERVER['REQUEST_URI'],
                'request_body' => $request_body,
                'response_code' => http_response_code(),
                'response_body' => ob_get_contents(),
                'timestamp' => date("Y-m-d H:i:s")
            ]);

            return true;
        } 
        catch (Exception $error) 
        {
            return false;
        }
    }

    private static function omitted($request_body)
    {
        // Remove passwords from array
        if (isset(json_decode($request_body, true)['password']))
        {
            $request_body = json_decode($request_body, true);
            $request_body['password'] = "omitted";
            $request_body = json_encode($request_body);
        }

        // Remove new password from array
        if (isset(json_decode($request_body, true)['new_password']))
        {
            $request_body = json_decode($request_body, true);
            $request_body['new_password'] = "omitted";
            $request_body = json_encode($request_body);
        }
        
        return $request_body;
    }

    private static function accountability()
    {
        try {
            // Check if Token was passed in request
            $token = (isset($_SERVER['HTTP_AUTHORIZATION']) ? str_replace('Bearer ', '', $_SERVER['HTTP_AUTHORIZATION']) : null);

            // Check the authentication is correct
            $check = \Auth\Auth::check($token);
            if (!$check)
            {
                throw new \Exception("You are not authorised to access this endpoint, please provide an API Key.", 403);    
            }
            $user = \Auth\Auth::get($token);

            return $user['id'];
        } catch (Exception $error) {
            return null;
        }
    }
}
