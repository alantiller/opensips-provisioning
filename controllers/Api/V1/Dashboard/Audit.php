<?php

/*
|--------------------------------------------------------------------------
| Dashboard Audit Controller
|--------------------------------------------------------------------------
*/

namespace Api\V1\Dashboard;

class Audit
{
    public static function errors()
    {
        global $local;

        try {
            // Check authentication before continuining
            self::authenticate();

            // Create response
            $response = array('data' => array());

            // Get all server from the gateways table
            $audits = $local->select('osp_audit', '*', ['response_code[!]' => '200', 'LIMIT' => 5, 'ORDER' => ['id' => 'DESC']]);

            // Loop through each gateway and get a count
            foreach ($audits as $audit) {
                array_push($response['data'], array('request_url' => $audit['request_url'], 'method' => $audit['method'], 'response_body' => $audit['response_body'], 'response_code' => $audit['response_code'], 'timestamp' => date( 'd/m/Y H:i', strtotime($audit['timestamp']))));
            }

            // Respond with the chart data
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
        global $local;

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
        $permission = \Auth\Permissions::canRead('osp_audit', $user['id']);
        if (!$permission)
        {
            $check = \Auth\Permissions::check('osp_audit', $user['id']);
            throw new \Exception("You account is not authorised to use this endpoint, please ask an admin to give you access." . json_encode($check), 403);    
        }

        return true;
    }
}
