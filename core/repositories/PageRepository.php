<?php
/**
 * Created by PhpStorm.
 * User: Marie CHARLES
 * Date: 14/06/2018
 * Time: 11:10
 */

namespace App\Repositories;


use App\Entities\Page;
use App\Helpers\Database;
use App\Helpers\Repository;

class PageRepository extends Repository
{

    /**
     * List all pages present in DB
     * @param string $slug
     * @param int $id
     * @return array
     */
    public function getAll($slug = '', $id = 0)
    {
        $sql = 'SELECT 
        `id`,
        `title`,
        `content`,
        `slug`,
        `content`,
        `visibility` 
        FROM `pages`';

        $sqlCond = [];

        if(!empty($slug)) {
            $sqlCond[] = '`slug` = :slug';
        }

        if(!empty($id)) {
            $sqlCond[] = '`id` = :id';
        }

        if (count($sqlCond) > 0) {
            $sql.= ' WHERE ' . implode(' AND' , $sqlCond);
        }

        $stmt = $this->getDB()->prepare($sql);

        if (!empty($slug)) {
            $stmt->bindValue('slug', $slug);
        }

        if (!empty($id)) {
            $stmt->bindValue('slug', $id);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $pages = [];
        foreach ($rows as $row) {
            $pages[] = new Page($row);
        }

        return $pages;
    }

    /**
     * Get a page by its ID
     * @param $id
     * @return array|Page
     */
    public function getById($id)
    {
       $page = current($this->getAll('',$id));

       return $page;
    }

    /**
     * Delete page by given ID
     * @param $id
     * @return bool
     */
    public function deleteById($id)
    {
        $sql = "DELETE from `pages`
        WHERE `id` = :id
        LIMIT 1";

        $stmt = $this->getDB()->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $this->errorManagement($stmt);

        return true;
    }
}