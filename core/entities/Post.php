<?php
namespace App\Entities;
use App\Helpers\Entity;

class Post extends Entity {
    /**
     * @var int
     */
    public $id = 0;

    /**
     * @var string
     */
    public $title = '';

    /**
     * @var string
     */
    public $content = '';

    /**
     * @var \DateTime
     */
    public $created_at;

    /**
     * @var \DateTime
     */
    public $updated_at;

    /**
     * @var int
     */
    public $visibility = 0;

    /**
     * Define Validation Rules concerning Post entities validations
     * @return array
     */
    public function rules(): array
    {
        return [
            'title' => [
            ]
        ];
    }
}
