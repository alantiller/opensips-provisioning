<?php

/*
|--------------------------------------------------------------------------
| Auth Users Controller
|--------------------------------------------------------------------------
*/

namespace Auth;

use \Exception;
use \Tokenly\TokenGenerator\TokenGenerator;

class Auth
{
    public static function authenticate($username, $password)
    {
        global $local;

        $users = $local->select('osp_users', '*', ['username' => $username]);

        if (count($users) != 1) { 
            throw new Exception('The email address or password was incorrect', 404);
        }

        $user = $users[0];
        $salted_hash = hash('sha256', $password . $user['salt']);
        
        if ($user['password'] != $salted_hash) {
            throw new Exception('The email address or password was incorrect', 404);
        }

        $auth_token = (new TokenGenerator())->generateToken(50);
        $local->insert("osp_tokens", [
            "user" => $user['id'],
            "token" => $auth_token,
            "expiry" => date("Y-m-d H:i:s", time() + $_ENV['SESSION_EXPIRY']),
            "timestamp" => date("Y-m-d H:i:s")
        ]);

        $_SESSION['osp_token'] = $auth_token;

        return true;
    }

    public static function check($token = null)
    {
        global $local;

        try {
            // Get the token if it's not been passed
            $token = ($token != null ? $token : $_SESSION['osp_token']);

            // Get all tokens matching the prodived in the user service
            $tokens = $local->select('osp_tokens', '*', ['token' => $token]);
    
            // Check if the token exists
            if (count($tokens) != 1) {
                throw new Exception("The token provided was not found in the database");
            }
            $token = $tokens[0];

            // Get the exipry time
            $current_expiry = strtotime($token['expiry']);
    
            // Check the session has not expired
            if (time() > $current_expiry) {
                $local->delete("osp_tokens", ["id" => $token['id']]);
                throw new Exception("The user session has timed out");
            }

            // Calculate new expiry date
            $new_expiry = time() + $_ENV['SESSION_EXPIRY'];

            // If the new expiry date is bigger than the old date set the new date
            if ($new_expiry > $current_expiry)
            {
                $local->update("osp_tokens", ["expiry" => date("Y-m-d H:i:s", $new_expiry)], ["id" => $token['id']]);
            }

            // Return as session is valid
            return true;
        } catch (Exception $error) {
            // Return as session is not valid
            return false;
        }
    }

    public static function get($token = null)
    {
        global $local;

        try {
            // Get the token if it's not been passed
            $token = ($token != null ? $token : $_SESSION['osp_token']);

            // Check the session is valid
            $check = self::check($token);
            if (!$check)
            {
                throw new Exception("The session is not valid", 403);  
            }

            // Get all tokens matching the prodived in the user service
            $token = $local->get('osp_tokens', ['id', 'user'], ['token' => $token]);

            // Get all tokens matching the prodived in the user service
            $user = $local->get('osp_users', '*', ['id' => $token['user']]);
            
            return $user;
        } catch (Exception $error) {
            return false;
        }
    }

    public static function destroy($token = null)
    {
        global $local;

        try {
            // Get the token if it's not been passed
            $token = ($token != null ? $token : $_SESSION['osp_token']);

            // Check the session is valid
            $check = self::check($token);
            if (!$check)
            {
                throw new Exception("The session is not valid", 403);  
            }

            // Delete the token from the database
            $local->delete('osp_tokens', ['token' => $token]);

            // Delete the session
            if (isset($_SESSION['osp_token']))
            {
                unset($_SESSION['osp_token']);
            }

            return true;
        } catch (Exception $error) {
            return false;
        }
    }
}

