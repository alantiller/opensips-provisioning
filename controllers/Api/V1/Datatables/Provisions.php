<?php

/*
|--------------------------------------------------------------------------
| Servers Controller
|--------------------------------------------------------------------------
*/

namespace Api\V1\Datatables;

class Provisions
{
    public static function get()
    {
        global $local;
        
        try {
            $columns = array(
                array( 'db' => 'id',             'dt' => 0 ),
                array( 'db' => 'server',         'dt' => 1 ),
                array( 'db' => 'enabled',        'dt' => 2,
                    'formatter' => function( $d, $row ) {
                        return ($d == 1 ? 'True' : 'False');
                    }
                ),
                array( 'db' => 'description',    'dt' => 3 ),
                array( 'db' => 'request_url',    'dt' => 4 ),
                array( 'db' => 'request_method', 'dt' => 5 ),
                array( 'db' => 'timestamp',      'dt' => 6,
                    'formatter' => function( $d, $row ) {
                        return date( 'd/m/Y H:i', strtotime($d));
                    }
                )
            );

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(\Datatables\SSP::simple( $_GET, $local->pdo, 'osp_provisions', 'id', $columns ));
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
