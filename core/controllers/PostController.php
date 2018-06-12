<?php

namespace App\Controllers;
use App\Entities\Post;
use App\Helpers\Entity;
use App\Repositories\PostRepository;
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
        $postRepository = new PostRepository();
        $post = new Post($_POST);
        $validateViolations = $post->validate();

        if (count($validateViolations)!== 0) {
            return $validateViolations;
        } else {
            $postRepository->create($post);
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
        $postRepository = new PostRepository();
        $posts = $postRepository->getAll();

        // Retrieve errors from Query Parameters and pass it to view
        $error = isset($_GET['error']) ? $_GET['error'] : '';
        return $this->render('posts/list.html.twig', [
            'posts' => $posts,
            'error' => $error,
        ]);
    }

    /**
     * View a post by id
     * @param int $id - Id of the post to view
     * @return string - HTML Structure for page posts/view
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function viewAction($id): string
    {
        $postModel = new PostRepository();
        $post = $postModel->getById($id);

        if (empty($post)) {
            $error = "Post with id: $id does not exist";
            header("Location: /posts?error=$error");
            exit;
        }

        return $this->render('posts/view.html.twig', ['post' => $post]);
    }
}
