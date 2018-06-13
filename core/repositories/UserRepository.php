<?php


namespace App\Repositories;

use App\Entities\User;
use App\Helpers\Repository;

class UserRepository extends Repository
{
    public function getByUsername($username)
    {
       $sql = "SELECT `username`,`password` FROM `users` WHERE `username` = :username";
       $stmt=$this->getDB()->prepare($sql);

        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $row = $stmt->fetchAll(\PDO::FETCH_ASSOC);

       if(empty($row)) {
           return [];
       } else {
           return new User(current($row));
       }
    }
}