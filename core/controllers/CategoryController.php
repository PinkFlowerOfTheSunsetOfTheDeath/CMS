<?php
namespace App\Controllers;
use App\Helpers\Controller;

/**
 * Class CategoryController
 * @package App\Controllers
 */
class CategoryController extends Controller
{
    /**
     * Create a Category via the category creation Form
     * @return string - HTML Layout for categories creation form
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function createAction(): string
    {
        return $this->render('categories/form.html.twig');
    }

    public function saveAction(): string
    {

    }
}