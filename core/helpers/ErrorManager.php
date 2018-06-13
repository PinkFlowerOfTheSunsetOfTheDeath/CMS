<?php
/**
 * Created by PhpStorm.
 * User: antoinemasselot
 * Date: 12/06/2018
 * Time: 22:10
 */

namespace App\Helpers;


class ErrorManager
{
    public static function setError($error)
    {
        $_SESSION['error'] = $error;
    }

    public static function getError()
    {
        if (isset($_SESSION['error'])) {
            return $_SESSION['error'];
        }
        return null;
    }

    public static function clearError()
    {
        unset($_SESSION['error']);
    }
}