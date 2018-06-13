<?php
namespace App\Entities;
use App\Helpers\Entity;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class Category
 * @package App\Entities
 */
class Category extends Entity
{
    public $id = 0;
    public $label = '';
    public $slug = '';

    /**
     * Validation rules for Category entity
     * @return array - array of validation rules
     */
    public function rules(): array
    {
        return [
            'slug' => [
                new NotBlank(),
                new Length([
                    'max' => 100,
                    'maxMessage' => 'must not exceed 100 characters long'
                ])
            ],
            'label' => [
                new NotBlank(),
                new Length([
                    'max' => 100,
                    'maxMessage' => 'must not exceed 100 characters long'
                ])
            ]
        ];
    }
}