<?php
namespace App\Controllers;
use App\Helpers\Controller;
use App\Repositories\PostRepository;

/**
 * Class FrontController
 * @package App\Controllers
 */
class FrontController extends Controller
{
    /**
     * Display the Home Page of the website on Front Office
     */
    public function homeAction()
    {
        $this->renderFront('home.php');
    }

    public function viewPostAction($slug)
    {
        // Retrieve post from database
        $postRepository = new PostRepository();
        $post = $postRepository->getBySlug($slug, 1);

        if (empty($post)) {
            // Redirect to error
            $this->renderFront('404.php');
        }

        $this->renderFront('article.php', ['post' => $post]);
    }
}