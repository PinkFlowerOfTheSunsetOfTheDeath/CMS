<?php
/**
 * Created by PhpStorm.
 * User: Marie CHARLES
 * Date: 12/06/2018
 * Time: 11:26
 */

class Database
{
    private static $db = null;

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