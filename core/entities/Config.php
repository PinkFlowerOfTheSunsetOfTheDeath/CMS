<?php
namespace App\Entities;
use App\Helpers\Entity;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class Config
 * @package App\Entities
 */
class Config extends Entity
{
    public $web;
    public $host;
    public $port;
    public $name;
    public $user;
    public $password;

    public function rules(): array
    {
        return [
            'host' => [
                new NotBlank()
            ],
            'port' => [
                new NotBlank()
            ],
            'name' => [
                new NotBlank()
            ],
            'user' => [
                new NotBlank()
            ],
            'password' => [
                new NotBlank()
            ]
        ];
    }
}