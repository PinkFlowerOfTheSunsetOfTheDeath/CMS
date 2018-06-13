<?php
namespace App\Controllers;
use App\Entities\Category;
use App\Helpers\Controller;
use App\Helpers\ErrorManager;
use App\Repositories\CategoryRepository;
use Twig\Error\Error;

/**
 * Class CategoryController
 * @package App\Controllers
 */
class CategoryController extends Controller
{
    const ERROR__NOT_FOUND = 'Category not found for id: ';

    /**
     * Create a Category via the category creation Form
     * @return string - HTML Layout for categories creation form
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function createAction(): string
    {
        return $this->render('categories/form.html.twig');
    }

    /**
     *
     * @return string
     */
    public function saveAction(): string
    {
        $category = new Category($_POST);
        $violations = $category->validate();

        if (!empty($violations)) {
            ErrorManager::setError($violations);
            $_SESSION['form'] = $_POST;
            $this->redirect('/categories/create');
            exit;
        }
        $categoryRepository = new CategoryRepository();
        // try to save category to database, if succeed, redirect to details page, else redirect to creation form
        try {
            $category = $categoryRepository->create($category);
            $this->redirect("/categories/$category->id");
            exit;
        } catch (\PDOException $e) {
            ErrorManager::setError([$e->getMessage()]);
            $this->redirect('/categories/create');
            exit;
        }
    }

    /**
     * Edit a category - get update form
     * @param int $id - ID of category to edit
     * @return string - HTML Layout for category edition
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function editAction(int $id): string
    {
        $categoryRepository = new CategoryRepository();
        $category = $categoryRepository->getById($id);

        if (empty($category)) {
            $error = self::ERROR__NOT_FOUND . $id;
            $this->redirectWithError('/categories', $error);
            exit;
        }

        return $this->render('categories/form.html.twig', ['category' => $category]);
    }
}