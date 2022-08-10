<?php

/*
|--------------------------------------------------------------------------
| Account Controller
|--------------------------------------------------------------------------
*/

namespace App;

class Account
{
    public static function view()
    {
        global $templates;

        self::authenticate();
        
        echo $templates->render('App::Account');
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

        return true;
    }
}
