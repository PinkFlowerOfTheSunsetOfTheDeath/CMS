<?php
namespace App\Repositories;
use App\Entities\Category;
use App\Helpers\Repository;

/**
 * Class CategoryRepository
 * @package App\Repositories
 */
class CategoryRepository extends Repository
{
    /**
     * @param string $slug
     * @param int $id
     * @param string label
     * @return array|Category[]
     */
    public function getAll(string $slug = '', int $id = 0, string $label = '')
    {
        $sql = 'SELECT `id`, `label`, `slug` FROM `categories`';
        $sqlCond = [];

        if (!empty($slug)) {
            $sqlCond[] = '`slug` = :slug';
        }

        if ($id > 0) {
            $sqlCond[] = '`id` = :id';
        }
        // IF
        if (count($sqlCond) > 0) {
            $sql .= ' WHERE ' . implode(' AND', $sqlCond);
        }

        $stmt = $this->getDB()->prepare($sql);

        if (!empty($slug)) {
            $stmt->bindValue(':slug', $slug);
        }
        if ($id > 0) {
            $stmt->bindValue(':id', $id);
        }
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $categories = [];
        foreach ($rows as $row) {
            $categories[] = new Category($row);
        }
        return $categories;
    }

    /**
     * Get A single category by id
     * @param int $id - ID of the category to fetch
     * @return array|Category - empty array if not found, else Category object
     */
    public function getById(int $id)
    {
        $category = current($this->getAll('', $id));
        return $category;
    }

    /**
     * Get A single category by slug
     * @param string $slug - Slug of the category to fetch
     * @return array|Category - empty array if not found, else Category object
     */
    public function getBySlug(string $slug)
    {
        $category = current($this->getAll($slug));
        return $category;
    }

    /**
     * Check whether a Category exists in database with given label
     * @param string $label - Label of category to check existence of
     * @return bool - true if category exists with given label, false if it does not
     */
    public function existsByLabel(string $label)
    {
        $category = $this->getAll('', 0, $label);
        return !empty($category);
    }

    /**
     * Create a category
     * @param Category $category - Category to create
     * @return Category - created category
     * @throws \PDOException
     */
    public function create(Category $category): Category
    {
        $db = $this->getDB();
        $sql = 'INSERT INTO `categories` (`label`, `slug`) VALUES(:label, :slug)';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':label', $category->label);
        $stmt->bindValue(':slug', $category->slug);
        $stmt->execute();

        $this->errorManagement($stmt);

        $category->id = $db->lastInsertId();
        return $category;
    }
}