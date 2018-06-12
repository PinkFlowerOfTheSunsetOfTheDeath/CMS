<?php

namespace App\Controllers;
use App\Models\PostModel;

class PostController extends BaseController
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

    /**
     * @param $data
     */
    public function saveAction($data)
    {
        $postModel = new PostModel();
        return $postModel->create($data);
    }
}