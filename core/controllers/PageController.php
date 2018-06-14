<?php
namespace App\Controllers;


use App\Entities\Page;
use App\Helpers\Controller;
use App\Repositories\PageRepository;

class PageController extends Controller
{
    const ERROR__PAGE_NOT_FOUND = 'Page not found for id: ';

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function listAction()
    {
        $pageRepository = new PageRepository();
        $pages = $pageRepository->getAll();

        $error = isset($_GET['error']) ? $_GET['error'] : '';

        return $this->render("pages/listPages.html.twig", [
            'pages' => array_reverse($pages),
            'error' => $error
        ]);
    }

    /**
     * Get page view at given id
     * @param $id
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
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

    public function editAction($id)
    {
        $pageRepository = new PageRepository();
        $page = $pageRepository->getById($id);

        if (empty($page)) {
            $error = self::ERROR__PAGE_NOT_FOUND . $id;
            $this->redirectWithError('/admin/pages', $error);
            exit;
        }

        return $this->render('pages/formPages.html.twig', [
            'page' => $page,
            'action' => 'edit'
        ]);
    }

    public function updateAction(int $id)
    {
        $pageRepository = new PageRepository();

        $page = $pageRepository->getById($id);

        if (empty($page)) {
            $error = self::ERROR__PAGE_NOT_FOUND . $id;
            $this->redirectWithError('/admin/pages', $error);
            exit;
        }

        unset($_POST['id']);
        $page->hydrate($_POST);

        // Validate user data
        $violations = $page->validate();
        if (!empty($violations)) {
            return $this->render('pages/formPages.html.twig', [
               'page' => $_POST,
               'errors' => $violations,
            ]);
        }

        try {
            $pageRepository->update($page);
        } catch (\PDOException $e) {
            $errors = [$e->getMessage()];
            return $this->render('page/formPages.html.twig', [
               'errors' => $errors,
                'page' => $page
            ]);
        }

        $this->redirect('/admin/pages');
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
            $this->redirectWithError('/admin/pages', $error);
            exit;
        }

        $pageRepository->deleteById($id);
        $this->redirect('/admin/pages');
    }


    /**
     * Render create page form
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function createAction()
    {
        return $this->render("pages/formPages.html.twig", [
            'action' => 'form'
        ]);
    }

    /**
     * Add new page in DB
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function saveAction()
    {
        $page = new Page($_POST);

        $violations = $page->validate();

        if (count($violations) !== 0) {
            return $this->render('pages/formPages.html.twig', [
                'page' => $page,
                'errors' => $violations,
                'action' => 'create'
            ]);
        }

        try {
            $pageRepository = new PageRepository();
            // Redirect to the list of pages
            $pageRepository->create($page);
            header("Location: /admin/pages");
            exit;
        } catch (\PDOException $e) {
            // Redirect to create page form with posted data
            return $this->render('pages/formPages.html.twig', [
                'page' => $page,
                'errors' => ['An error occurred while creating the category in the Database'],
                'action' => 'create'
            ]);
        }

    }
}