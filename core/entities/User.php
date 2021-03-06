<?php

namespace App\Entities;
use App\Helpers\Entity;
use Symfony\Component\Validator\Constraints\NotBlank;

class User extends Entity
{
    const ROLE_ADMIN = 'admin';

    /**
     * @var string
     */
    public $username = '';

    /**
     * @var string
     */
    public $email = '';

    /**
     * @var string
     */
    public $password = '';

    /**
     * @var string
     */
    public $token = '';

    /**
     * @var string
     */
    public $role = '';

    /**
     * Define Validation Rules concerning User entities validations
     * @return array
     */
    public function rules(): array
    {
        return [
            'username' => [
                new NotBlank()
            ],
            'email' => [
                new NotBlank()
            ],
            'password' => [
                new NotBlank()
            ]
        ];
    }

    /**
     * @param $password
     * @return bool
     */
    public function checkPassword($password)
    {
        $hashedPassword = hash("sha512", $password);

        return $hashedPassword == $this->password;
    }

    /**
     * Hash a given password and set it to Entity
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $hashedPassword = hash("sha512", $password);
        $this->password = $hashedPassword;
    }

    /**
     * Return whether user is admin
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if given token matches User's token
     * @param string $token
     * @return bool
     */
    public function isTokenValid(string $token)
    {
        return $this->token === $token;
    }

    /**
     * @return string
     */
    public function generateToken()
    {
        $this->token = 'zoiehgoshglis';
    }
}