<?php

namespace App\Controllers;
use App\Models\PostModel;
use Symfony\Component\HttpFoundation\Request;

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
        return $this->render('posts/form.html.twig');
    }

    /**
     * @param $request
     * @return string
     */
    public function saveAction(Request $request)
    {

        //$postModel = new PostModel();
        //return $postModel->create($request);
    }

    /**
     * List all Posts from Database
     * @return string - Html Structure for posts list page
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function listAction(): string
    {
        $postModel = new PostModel();
        $posts = $postModel->getAll();

        return $this->render('posts/list.html.twig', ['posts' => $posts]);
    }
}