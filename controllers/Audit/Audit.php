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

            // Remove passwords from array
            if (isset(json_decode($request_body, true)['password']))
            {
                $request_body = json_decode($request_body, true);
                $request_body['password'] = "omitted";
                $request_body['new_password'] = "omitted";
                $request_body = json_encode($request_body);
            }

            // Create the audit log
            $local->insert('osp_audit', [
                'method' => $_SERVER['REQUEST_METHOD'],
                'request_url' => $_SERVER['REQUEST_URI'],
                'request_body' => $request_body,
                'response_code' => http_response_code(),
                'response_body' => ob_get_contents(),
                'timestamp' => date("Y-m-d H:i:s")
            ]);

            return true;
        } 
        catch (\Exception $error) 
        {
            return false;
        }
    }
}
