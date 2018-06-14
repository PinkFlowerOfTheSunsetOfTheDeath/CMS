<?php
namespace App\Controllers;


use App\Entities\Page;
use App\Helpers\Controller;
use App\Helpers\ErrorManager;
use App\Repositories\PageRepository;

class PageController extends Controller
{
    const ERROR__PAGE_NOT_FOUND = 'Page not found for id: ';

    /**
     * @return string
     */
    public function listAction()
    {
        $pageRepository = new PageRepository();
        $pages = $pageRepository->getAll();

        $error = isset($_GET['error']) ? $_GET['error'] : '';

        return $this->render("pages/listPages.html.twig", [
            'pages' => $pages,
            'error' => $error
        ]);
    }

    /**
     * Get page view at given id
     * @param $id
     * @return string
     */
    public function viewAction($id)
    {
        $pageRepository = new PageRepository();
        $page = $pageRepository->getById($id);
        

        if (empty($page)) {
            $error = self::ERROR__PAGE_NOT_FOUND . $id;
            $this->redirectWithError('/pages', $error);
            exit;
        }

        return $this->render("pages/viewPage.html.twig", [
            'page' => $page
        ]);
    }


    /**
     * Delete page by given id
     * @param $id
     */
    public function deleteAction($id)
    {
        $pageRepository = new PageRepository();
        $page = $pageRepository->getById($id);


        if (empty($page)) {
            $error = self::ERROR__PAGE_NOT_FOUND . $id;
            $this->redirectWithError('/pages', $error);
            exit;
        }

        $pageRepository->deleteById($id);
        header("Location: /pages");
    }

    /**
     * Render create page form
     * @return string
     */
    public function createAction()
    {
        return $this->render("pages/formPages.html.twig", [
            'action' => 'form'
        ]);
    }

    public function saveAction()
    {
        $pageRepository = new PageRepository();
        $page = new Page($_POST);

        $violations = $page->validate();

        if (count($violations) !== 0) {
            return $this->render('pages/form.html.twig', [
                'page' => $page,
                'errors' => $violations,
                'action' => 'create'
            ]);
        }

        try {
            // Redirect to the list of pages
            $pageRepository->create($page);
            header("Location: /pages");
            exit;
        } catch (\PDOException $e) {
            // Redirect to create page form with posted data
            return $this->render('pages/form.html.twig', [
                'pageData' => $page,
                'errors' => ['An error occurred while creating the category in the Database'],
                'action' => 'create'
            ]);
        }

    }
}