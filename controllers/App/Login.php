<?php

/*
|--------------------------------------------------------------------------
| Subscribers Controller
|--------------------------------------------------------------------------
*/

namespace App;

class Login
{
    public static function view()
    {
        global $templates;
        
        echo $templates->render('App::Login');
    }
}
