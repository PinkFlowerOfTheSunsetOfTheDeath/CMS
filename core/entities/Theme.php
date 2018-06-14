<?php
namespace App\Entities;
use App\Helpers\Entity;

/**
 * Class Theme
 * @package App\Entities
 */
class Theme extends Entity
{
    public $image = '';
    public $name = '';
    public $selected = false;
}