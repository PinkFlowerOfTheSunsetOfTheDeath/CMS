<?php


namespace App\Repositories;

use App\Entities\User;
use App\Helpers\Repository;

class UserRepository extends Repository
{
    /**
     * @param $username
     * @return User|array
     */
    public function getByUsername($username)
    {
       $sql = "SELECT 
                `users`.`username`,
                `users`.`password`,
                `users`.`token`,
                `users`.`email`,
                `roles`.`label` as `role`
               FROM `users`
               INNER JOIN 
                 `roles`
               ON `roles`.`id` = `users`.`role_id`
               WHERE `username` = :username";
       $stmt=$this->getDB()->prepare($sql);

        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $row = $stmt->fetchAll(\PDO::FETCH_ASSOC);

       if(empty($row)) {
           return [];
       } else {
           $userData = current($row);
           return new User($userData);
       }
    }
}