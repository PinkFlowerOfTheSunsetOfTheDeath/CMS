<?php
/**
 * Created by PhpStorm.
 * User: Marie CHARLES
 * Date: 14/06/2018
 * Time: 11:03
 */

namespace App\Entities;


use App\Helpers\Entity;
use Symfony\Component\Validator\Constraints\NotBlank;

class Page extends Entity
{
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
    public $slug = '';

    /**
     * @var string
     */
    public $content = '';

    /**
     * @var int
     */
    public $visibility = 0;


    public function rules() : array {
        return [
           'title' => [
               new NotBlank()
           ],
            'slug' => [
                new NotBlank()
            ]
        ];
    }

}