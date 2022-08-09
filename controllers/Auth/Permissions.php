<?php

/*
|--------------------------------------------------------------------------
| Auth Permissions Controller
|--------------------------------------------------------------------------
*/

namespace Auth;

use \Exception;

class Permissions
{
    public static function check($entity, $user)
    {
        global $local;

        try {
            // Check the user exists
            $user = $local->get('osp_users', '*', ['id' => $user]);
            if ($user == null)
            {
                throw new Exception("We could not find the user.", 404);
            }

            // Get the permission from the database
            $permission = $local->get('osp_permissions', '*', ['entity' => $entity, 'group' => $user['group']]);
            if ($permission == null || $permission['value'] == 0)
            {
                throw new Exception("You do not have access to this resource", 403);
            }

            // Calculate binary
            $binary = sprintf('%04d', decbin($permission['value']));
            $output = str_split($binary);

            // Return entity permissions
            return array('delete' => intval($output[0]), 'create' => intval($output[1]), 'write' => intval($output[2]), 'read' => intval($output[3]));
        } catch (Exception $error) {
            return false;
        }


        
    }

    public static function canRead($entity, $user)
    {
        try {
            $check = self::check($entity, $user);

            if ($check['read'] != 1)
            {
                throw new Exception("User is not permitted to read the entity requested", 403);
            }

            return true;
        } catch (Exception $error) {
            return false;
        }
    }

    public static function canWrite($entity, $user)
    {
        try {
            $check = self::check($entity, $user);

            if ($check['write'] != 1)
            {
                throw new Exception("User is not permitted to write the entity requested", 403);
            }

            return true;
        } catch (Exception $error) {
            return false;
        }
    }

    public static function canCreate($entity, $user)
    {
        try {
            $check = self::check($entity, $user);

            if ($check['create'] != 1)
            {
                throw new Exception("User is not permitted to create the entity requested", 403);
            }

            return true;
        } catch (Exception $error) {
            return false;
        }
    }

    public static function canDelete($entity, $user)
    {
        try {
            $check = self::check($entity, $user);

            if ($check['delete'] != 1)
            {
                throw new Exception("User is not permitted to delete the entity requested", 403);
            }

            return true;
        } catch (Exception $error) {
            return false;
        }
    }
}

