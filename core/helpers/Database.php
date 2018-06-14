<?php
namespace App\Helpers;
class Database
{
    /**
     * Database Server Host
     * @var string
     */
    private static $host = '';

    /**
     * Database Server Port
     * @var int
     */
    private static $port = 3306;

    /**
     * Database Name
     * @var string
     */
    private static $name = 'myte';

    /**
     * Database User
     * @var string
     */
    private static $user = 'root';

    /**
     * Database Password
     * @var string
     */
    private static $password = 'root';

    /**
     * @var \PDO
     */
    private static $db = null;

    /**
     *
     * @param array $config - array of configuration for database
     */
    public static function configureDB(array $config)
    {
        self::$host = $config['host'];
        self::$port = $config['port'];
        self::$name = $config['name'];
        self::$user = $config['user'];
        self::$password = $config['password'];
    }

    public static function getDSN()
    {
        return 'mysql:host=' . self::$host . ';dbname=' . self::$name . ';port=' . self::$port;
    }

    /**
     * Get PDO instance from user database config
     * @return \PDO
     */
    public static function getDB()
    {
        if (is_null(self::$db)) {
            $dsn = self::getDSN();
            self::$db = new \PDO(
                $dsn,
                self::$user,
                self::$password
            );
            self::$db->exec("SET NAMES UTF8");
        }

        return self::$db;
    }

    public static function initDatabase(string $sql)
    {
        //
        $stmt = self::getDB()->prepare($sql);
        $stmt->execute();
    }
}