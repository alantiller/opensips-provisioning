<?php

/*
|--------------------------------------------------------------------------
| OpenSIPS Logs Controller
|--------------------------------------------------------------------------
*/

namespace App\Logs;

class OpenSIPS
{
    public static function view()
    {
        global $templates;

        self::authenticate();

        if (isset($_ENV['OPENSIPS_LOG_PATH']) && file_exists('../' . $_ENV['OPENSIPS_LOG_PATH'])) {
            $content = file_get_contents('../' . $_ENV['OPENSIPS_LOG_PATH']);
        } else {
            $content = 'File was not found.';
        }

        echo $templates->render('App::Logs/OpenSIPS', ['content' => $content]);
    }

    // This function authenticates the page and checks the user should have access
    private static function authenticate()
    {
        global $router;

        // Check the authentication is correct
        $check = \Auth\Auth::check();
        if (!$check)
        {
            header('location: ' . $_ENV['PUBLIC_URL'] . $router->generate('login'));
            die();
        }
        $user = \Auth\Auth::get();

        // Check the user has permission
        $permission = \Auth\Permissions::canRead('local_opensipslogs', $user['id']);
        if (!$permission)
        {
            header('location: ' . $_ENV['PUBLIC_URL'] . $router->generate('dashboard'));
            die();
        }

        return true;
    }
}