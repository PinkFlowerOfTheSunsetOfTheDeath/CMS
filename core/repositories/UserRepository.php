<?php


namespace App\Repositories;

use App\Entities\User;
use App\Helpers\Database;
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

    /**
     * Create User in Database
     * @param User $user
     * @param int $role
     * @return bool
     */
    public function create(User $user, int $role)
    {
        $sql = 'INSERT INTO `users` 
                  (`username`, `password`, `email`, `role_id`, `token`)
                VALUES(:username, :password, :email, :role, :token)';

        $stmt = $this->getDB()->prepare($sql);
        $stmt->bindValue(':username', $user->username);
        $stmt->bindValue(':password', $user->password);
        $stmt->bindValue(':email', $user->email);
        $stmt->bindValue(':role', $role);
        $stmt->bindValue(':token', $user->token);

        $stmt->execute();

        return true;
    }

    /**
     * Check if an admin user exists in database
     * @return bool
     */
    public function adminExists()
    {
        $sql = 'SELECT COUNT(*) FROM `users` WHERE `role_id` = 1';
        $stmt = $this->getDB()->prepare($sql);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return $count > 0;
    }
}