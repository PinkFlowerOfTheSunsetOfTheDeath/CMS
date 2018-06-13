<?php
namespace App\Helpers;
class Repository
{
    /**
     * @var \PDO
     */
    private $db;

    public function __construct()
    {
        $this->db = Database::getDB();
    }

    /**
     * Will be called for each model to get Database
     * @return \PDO
     */
    protected function getDB()
    {
        return $this->db;
    }

    /**
     * Check if a PDO statement includes an error, throw an error if it does
     * @param \PDOStatement $stmt - PDO statement to manage error for
     * @throws \PDOException - Throws PDO Exception with error found in statement
     */
    public function errorManagement(\PDOStatement $stmt)
    {
        if ($stmt->errorCode() !== '00000') {
            throw new \PDOException(debug_backtrace()[1]['class'].'::'
                .debug_backtrace()[1]['function']);
        }
    }
}