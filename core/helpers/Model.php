<?php
/**
 * Created by PhpStorm.
 * User: Marie CHARLES
 * Date: 12/06/2018
 * Time: 11:34
 */

class Model
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getDB();
    }

    public function getDB()
    {
        return $this->db;
    }
}