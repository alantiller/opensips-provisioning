<?php

/*
|--------------------------------------------------------------------------
| Permissions Controller
|--------------------------------------------------------------------------
*/

namespace Api\V1\Datatables;

class Permissions
{
    public static function get()
    {
        global $local;

        try {
            self::authenticate();

            $columns = array(
                array( 'db' => 'id',        'dt' => 0 ),
                array( 'db' => 'group',     'dt' => 1 ),
                array( 'db' => 'entity',    'dt' => 2 ),
                array( 'db' => 'value',     'dt' => 3,
                    'formatter' => function( $d, $row ) {
                        return (str_split(sprintf('%04d', decbin($d)))[3] == 1 ? 'Yes' : 'No');
                    }
                ),
                array( 'db' => 'value',     'dt' => 4,
                    'formatter' => function( $d, $row ) {
                        return (str_split(sprintf('%04d', decbin($d)))[2] == 1 ? 'Yes' : 'No');
                    }
                ),
                array( 'db' => 'value',     'dt' => 5,
                    'formatter' => function( $d, $row ) {
                        return (str_split(sprintf('%04d', decbin($d)))[1] == 1 ? 'Yes' : 'No');
                    }
                ),
                array( 'db' => 'value',     'dt' => 6,
                    'formatter' => function( $d, $row ) {
                        return (str_split(sprintf('%04d', decbin($d)))[0] == 1 ? 'Yes' : 'No');
                    }
                ),
                array( 'db' => 'timestamp', 'dt' => 7,
                    'formatter' => function( $d, $row ) {
                        return date( 'd/m/Y H:i', strtotime($d));
                    }
                )
            );

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(\Datatables\SSP::simple( $_GET, $local->pdo, 'osp_permissions', 'id', $columns ));
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
        $permission = \Auth\Permissions::canRead('osp_permissions', $user['id']);
        if (!$permission)
        {
            throw new \Exception("You account is not authorised to use this endpoint, please ask an admin to give you access.", 403);    
        }

        return true;
    }
}
