<?php

/*
|--------------------------------------------------------------------------
| Subscribers Controller
|--------------------------------------------------------------------------
*/

namespace Api\V1\Datatables;

class Subscribers
{
    public static function get()
    {
        global $opensips;

        try {
            self::authenticate();

            $columns = array(
                array( 'db' => 'id',        'dt' => 0 ),
                array( 'db' => 'uuid',      'dt' => 1 ),
                array( 'db' => 'username',  'dt' => 2 ),
                array( 'db' => 'domain',    'dt' => 3 ),
                array( 'db' => 'attribute', 'dt' => 4 ),
                array( 'db' => 'type',      'dt' => 5 ),
                array( 'db' => 'value',     'dt' => 6 )
            );

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(\Datatables\SSP::simple( $_GET, $opensips->pdo, 'usr_preferences', 'id', $columns ));
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
        $permission = \Auth\Permissions::canRead('usr_preferences', $user['id']);
        if (!$permission)
        {
            throw new \Exception("You account is not authorised to use this endpoint, please ask an admin to give you access.", 403);    
        }

        return true;
    }
}
