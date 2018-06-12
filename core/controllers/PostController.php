<?php

namespace App\Controllers;
use App\Entities\Post;
use App\Helpers\Entity;
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
     * @return string|array
     */
    public function saveAction()
    {
        $postModel = new PostModel();
        $post = new Post($_POST);
        $validateViolations = $post->validate();

        if (count($validateViolations)!== 0) {
            return $validateViolations;
        } else {
            $postModel->create($post);
        }

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