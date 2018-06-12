<?php
/**
 * Created by PhpStorm.
 * User: Marie CHARLES
 * Date: 12/06/2018
 * Time: 10:55
 */

namespace App\Controllers;


class  PostController extends BaseController
{

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function createAction()
    {
        return $this->render('newPost.html.twig');
    }

    public function saveAction()
    {

    }
}