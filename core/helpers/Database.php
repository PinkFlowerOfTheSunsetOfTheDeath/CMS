<?php

namespace App\Helpers;

class Database
{
    private static $db = null;

    /**
     * Get PDO instance from user database config
     * @return null|PDO
     */
    public static function getDB()
    {
        if (is_null(self::$db)) {
            try {
                self::$db = new \PDO(
                    'mysql:host=localhost;dbname=root;port=3306,
                    root,
                    root'
                );
                self::$db->exec("SET NAMES UTF8");
            } catch (\PDOException $exception) {
                die("Error found due to PDO");
            }
        }

        return self::$db;
    }
}