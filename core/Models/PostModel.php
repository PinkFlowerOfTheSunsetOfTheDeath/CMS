<?php

namespace App\Models;
use App\Helpers\Model;

class PostModel extends Model
{
    /**
     * @param $data
     * @return string
     */
    public function create($data)
    {
        $sql = "INSERT INTO `posts`
        (`title`,
        `content`,
        `visibility`,
        `slug`)
        VALUES
        (:title,
        :content,
        :visibility,
        :slug
        )";

        $db = $this->getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':title', $data['title']);
        $stmt->bindValue(':content', $data['content']);
        $stmt->bindValue(':visibility', $data['visibility']);
        $stmt->bindValue(':slug', $data['slug']);

        $stmt->execute;

        return $db->lastInsertId();
    }

    public function getAll($slug = null, $id = null)
    {
        $sql = 'SELECT `id`, `title`, `content`, `created_at`, `updated_at`, `visibility`, `slug` FROM `posts`';

        $sqlCond = [];
        if (!is_null($slug)) {
            $sqlCond[] = '`slug` = :slug\n';
        }
        if (!is_null($id)) {
            $sqlCond[] = '`id` = :id\n';
        }

        if (count($sqlCond) > 0) {
            $sql .= 'WHERE ' . implode(" AND", $sqlCond);
        }

        $stmt = $this->getDB()->prepare($sql);
        if (!is_null($slug)) {
            $stmt->bindValue(':slug', $slug);
        }
        if (!is_null($id)) {
            $stmt->bindValue(':id', $id);
        }
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function findAll()
    {
        $db = $this->getDB();
        $sql = 'SELECT `id`, `title`, `content`, `created_at`, `updated_at`, `visibility`, `slug` FROM `posts`';
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return $rows;
    }
}