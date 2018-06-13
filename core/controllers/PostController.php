<?php

namespace App\Controllers;
use App\Entities\Post;
use App\Helpers\ErrorManager;
use App\Repositories\PostRepository;
use App\Helpers\Controller;

/**
 * Class PostController
 * @package App\Controllers
 */
class  PostController extends Controller
{
    const ERROR__POST_NOT_FOUND = 'Post does not exist with ID: ';
    const ERROR__DATABASE = 'An Error occured while trying to reach Database';

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
            $error = self::ERROR__POST_NOT_FOUND . $id;
            $this->redirectWithError('/posts', $error);
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
        $postRepository = new PostRepository();
        $postToDelete = $postRepository->getById($id);
        if (empty($postToDelete)) {
            header("Location: /posts");
            exit;
        }
        $postRepository->deleteById($id);
        header("Location: /posts");
    }

    /**
     * Edit a post (display edit form), redirect with error if post does not exist
     * @param int $id - Id of the post to edit
     * @return string - HTML Structure for page edit
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function editAction(int $id)
    {
        $postRepository = new PostRepository();
        $post = $postRepository->getById($id);

        if (empty($post)) {
            $error = self::ERROR__POST_NOT_FOUND . $id;
            $this->redirectWithError("/posts", $error);
            exit;
        }

        // If form data found in session, use it to refill the update form
        if (isset($_SESSION['form'])) {
            $post = new Post($_SESSION['form']);
        }

        return $this->render('posts/form.html.twig', ['post' => $post]);
    }

    /**
     * Update a given Post and redirect to post listing, display error if encountered any
     * @param int $id - ID of the post to update
     */
    public function updateAction(int $id): void
    {
        // Find Post by Given ID
        $postRepository = new PostRepository();
        $post = $postRepository->getById($id);

        // If Post not found, redirect with error
        if (empty($post)) {
            $error = self::ERROR__POST_NOT_FOUND . $id;
            $this->redirectWithError('/posts', $error);
            exit;
        }

        // Remove post id from POST object
        unset($_POST['id']);
        // Hydrate post entity with user data
        $post->hydrate($_POST);

        // Update post in Database
        try {
            $postRepository->update($post);
        } catch (\PDOException $e) {
            $error = self::ERROR__DATABASE;
            // Save form data to Session to refill form
            $_SESSION['form'] = $_POST;
            // Redirect with error
            $this->redirectWithError("/posts/$id/edit", $error);
            exit;
        }

        $this->redirect('/posts');
    }
}
