<?php

namespace App\Helpers;

class Model
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getDB();
    }

    /**
     * Will be called for each model to get Database
     * @return null|PDO
     */
    protected function getDB()
    {
        return $this->db;
    }
}