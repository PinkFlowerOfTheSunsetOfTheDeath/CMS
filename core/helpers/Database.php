<?php
namespace App\Helpers;
class Database
{
    /**
     * @var \PDO
     */
    private static $db = null;

    /**
     * Get PDO instance from user database config
     * @return \PDO
     */
    public static function getDB()
    {
        if (is_null(self::$db)) {
//            try {
                self::$db = new \PDO(
                    'mysql:host=127.0.0.1;dbname=pinkflowers;port=3306',
                    'root',
                    'root'
                );
                self::$db->exec("SET NAMES UTF8");
//            } catch (\PDOException $exception) {
//                var_dump($exception);
//                die("Error found due to PDO: " . $exception->getMessage());
//            }
        }

        return self::$db;
    }
}