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
}