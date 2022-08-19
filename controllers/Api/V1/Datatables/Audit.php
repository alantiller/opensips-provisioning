<?php

/*
|--------------------------------------------------------------------------
| Permissions Controller
|--------------------------------------------------------------------------
*/

namespace Api\V1\Datatables;

class Audit
{
    public static function get()
    {
        global $local;

        try {
            self::authenticate();

            $columns = array(
                array( 'db' => 'id',            'dt' => 0 ),
                array( 'db' => 'user',          'dt' => 1,
                    'formatter' => function( $d, $row ) {
                        global $local;
                        if ($d != 0 && $d != NULL && $d != "") {
                            return $local->get('osp_users', 'name', ['id' => $d]);
                        } else {
                            return '';
                        }
                    }
                ),
                array( 'db' => 'response_code', 'dt' => 2 ),
                array( 'db' => 'method',        'dt' => 3 ),
                array( 'db' => 'request_url',   'dt' => 4 ),
                array( 'db' => 'request_body',  'dt' => 5 ),
                array( 'db' => 'response_body', 'dt' => 6 ),
                array( 'db' => 'timestamp',     'dt' => 7,
                    'formatter' => function( $d, $row ) {
                        return date( 'd/m/Y H:i', strtotime($d));
                    }
                )
            );

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(\Datatables\SSP::simple( $_GET, $local->pdo, 'osp_audit', 'id', $columns ));
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
            throw new \Exception("You account is not authorised to use this endpoint, please ask an admin to give you access.", 403);    
        }

        return true;
    }
}
