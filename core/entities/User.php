<?php

namespace App\Entities;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Tests\Fixtures\Entity;

class User extends Entity
{
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

    public function checkPassword($password)
    {
        $hashedPassword = hash("sha512", $password);

        return $hashedPassword == $this->password;
    }
}