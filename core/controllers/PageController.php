<?php
namespace App\Controllers;


use App\Helpers\Controller;
use App\Repositories\PageRepository;

class PageController extends Controller
{
    const ERROR__NOT_FOUND = 'Page not found for id: ';

    /**
     * @return string
     */
    public function listAction()
    {
        $pageRepository = new PageRepository();
        $pages = $pageRepository->getAll();

        $error = isset($_GET['error']) ? $_GET['error'] : '';

        dump($pages);
        die();
        return $this->render("pages/listPages.html.twig", [
            'pages' => $pages,
            'error' => $error
        ]);
    }
}