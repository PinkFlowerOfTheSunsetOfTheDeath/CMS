<?php
namespace App\Controllers;
use App\Helpers\Controller;
use App\Repositories\PageRepository;
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

    /**
     * View a post with given slug, if not found, show 404
     * @param $slug
     */
    public function viewPostAction($slug)
    {
        // Retrieve post from database
        $postRepository = new PostRepository();
        $post = $postRepository->getBySlug($slug, 1);

        if (empty($post)) {
            http_response_code(404);
            // Redirect to error
            $this->renderFront('404.php');
        }

        $this->renderFront('article.php', ['post' => $post]);
    }

    /**
     * View a page by given slug
     * @param string $slug - Slug of the page to view
     */
    public function viewPageAction(string $slug)
    {
        $pageRepository = new PageRepository();
        $page = $pageRepository->getBySlug($slug, 1);

        if (empty($page)) {
            // page not found
            http_response_code(404);
            $this->renderFront('404.php');
        }

        $this->renderFront('page.php', ['page' => $page]);
    }
}