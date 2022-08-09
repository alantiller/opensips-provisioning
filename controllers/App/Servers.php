<?php

/*
|--------------------------------------------------------------------------
| Servers Controller
|--------------------------------------------------------------------------
*/

namespace App;

class Servers
{
    public static function view()
    {
        global $templates;
        
        self::authenticate();

        echo $templates->render('App::Servers');
    }

    // This function authenticates the API with the API key and errors if the API key is wrong
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
        $permission = \Auth\Permissions::canRead('osp_servers', $user['id']);
        if (!$permission)
        {
            header('location: ' . $_ENV['PUBLIC_URL'] . $router->generate('dashboard'));
        }

        return true;
    }
}