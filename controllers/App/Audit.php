<?php

/*
|--------------------------------------------------------------------------
| Audit Logs Controller
|--------------------------------------------------------------------------
*/

namespace App;

class Audit
{
    public static function view()
    {
        global $templates;
        
        self::authenticate();

        echo $templates->render('App::Audit');
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
        $permission = \Auth\Permissions::canRead('osp_audit', $user['id']);
        if (!$permission)
        {
            header('location: ' . $_ENV['PUBLIC_URL'] . $router->generate('dashboard'));
        }

        return true;
    }
}