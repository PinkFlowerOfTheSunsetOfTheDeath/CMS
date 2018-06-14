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
     * List all categories found
     * @return string - HTML Layout for list categories page
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function listAction()
    {
        $categoryRepository = new CategoryRepository();
        $categories = $categoryRepository->getAll();

        $error = isset($_GET['error']) ? $_GET['error'] : '';

        return $this->render('categories/categories.html.twig', [
            'categories' => $categories,
            'error' => $error
        ]);



    }

    /**
     * View a Category in details, by id
     * @param int $id - ID of the category to view
     * @return string - HTML layout for detailed category page
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function viewAction(int $id): string
    {
        $categoryRepository = new CategoryRepository();
        $category = $categoryRepository->getById($id);

        if (empty($category)) {
            $error = self::ERROR__NOT_FOUND . $id;
            $this->redirectWithError('/categories', $error);
            exit;
        }

        return $this->render('categories/view.html.twig', ['category' => $category]);
    }

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
     * Save A Category in the Database, validate User Input and manage errors
     * @return string - HTML Layout for form if
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function saveAction(): string
    {
        $category = new Category($_POST);
        $violations = $category->validate();

        if (!empty($violations)) {
            return $this->render('categories/form.html.twig', [
                'category' => $category,
                'errors' => $violations,
            ]);
        }
        $categoryRepository = new CategoryRepository();
        // try to save category to database, if succeed, redirect to details page, else redirect to creation form
        try {
            $category = $categoryRepository->create($category);
            $this->redirect("/categories/$category->id");
            exit;
        } catch (\PDOException $e) {
            return $this->render('categories/form.html.twig', [
                'category' => $category,
                'errors' => ['An error occurred while creating the category in the Database'],
            ]);
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

    /**
     * Update a Category in database, validate user input and manage errors
     * @param int $id - ID of category to update
     * @return string - HTML Layout for edition form
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function updateAction(int $id)
    {
        $categoryRepository = new CategoryRepository();
        $category = $categoryRepository->getById($id);

        if (empty($category)) {
            $error = self::ERROR__NOT_FOUND . $id;
            $this->redirectWithError('/categories/', $error);
            exit;
        }
        // Unset ID if set in POST Object
        unset($_POST['id']);
        // Hydrate category with User Input
        $category->hydrate($_POST);
        // Validate User Input Data, if error found, rerender edition form with errors
        $violations = $category->validate();
        if (!empty($violations)) {
            return $this->render('categories/form.html.twig', [
                'category' => $category,
                'errors' => $violations
            ]);
        }

        try {
            $categoryRepository->update($category);
            $this->redirect("/categories/$id");
        } catch (\PDOException $e) {
            return $this->render('categories/form.html.twig', [
                'category' => $category,
                'errors' => ['An error occured while processing category update in the Database']
            ]);
        }
    }

    /**
     * Delete one category by its given id
     * @param $id
     */
    public function deleteAction($id)
    {
        $categoryRepository = new CategoryRepository();
        $categoryToDelete = $categoryRepository->getById($id);
        if (empty($categoryToDelete)) {
            header("Location: /categories");
            exit;
        }
        $categoryRepository->delete($id);
        header("Location: /categories");
    }
}