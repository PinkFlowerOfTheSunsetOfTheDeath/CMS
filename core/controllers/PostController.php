<?php

namespace App\Controllers;
use App\Entities\Post;
use App\Helpers\ErrorManager;
use App\Repositories\PostRepository;

/**
 * Class PostController
 * @package App\Controllers
 */
class  PostController extends BaseController
{
    /**
     * Display the creation form for Post Entity, with errors if there are any
     * @return string - HTML Structure for Post creation page
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function createAction(): string
    {
        // Get Errors from Manager
        $errors = ErrorManager::getError();
        // Clear Errors from Session
        ErrorManager::clearError();
        return $this->render('posts/form.html.twig', ['errors' => $errors]);
    }

    /**
     * Save a Post in Database, and redirect user to created post details page, or redirect to creation form with errors
     * if there are any
     */
    public function saveAction(): void
    {
        $postRepository = new PostRepository();
        $post = new Post($_POST);
        $validateViolations = $post->validate();

        // If any validation rules failed, redirect user to post creation page
        if (count($validateViolations) !== 0) {
            ErrorManager::setError($validateViolations);
            $_SESSION['form'] = $_POST;
            header('Location: /posts/create');
            exit;
        }
        // Redirect to created post details page
        $createdPost = $postRepository->create($post);
        header("Location: /posts/$createdPost->id");
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
        $postRepository = new PostRepository();
        $post = $postRepository->getById($id);

        if (empty($post)) {
            $error = "Post with id: $id does not exist";
            header("Location: /posts?error=$error");
            exit;
        }

        return $this->render('posts/view.html.twig', ['post' => $post]);
    }

    /**
     * Delete one post by its given id
     * @param $id
     */
    public function deleteAction($id)
    {
        $postModel = new PostRepository();
        $postModel->deleteById($id);
        header("Location: /posts");

    }
}
