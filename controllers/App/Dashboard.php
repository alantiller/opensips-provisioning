<?php

/*
|--------------------------------------------------------------------------
| Dashboard Controller
|--------------------------------------------------------------------------
*/

namespace App;

class Dashboard
{
    public static function view()
    {
        global $templates;

        self::authenticate();
        
        echo $templates->render('App::Dashboard');
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