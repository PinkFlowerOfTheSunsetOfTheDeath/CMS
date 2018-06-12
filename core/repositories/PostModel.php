<?php

namespace App\Models;
use App\Entities\Post;
use App\Helpers\Model;

class PostModel extends Model
{
    /**
     * @param $data
     * @return string
     */
    public function create($post)
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

        $stmt->bindValue(':title', $post->title);
        $stmt->bindValue(':content', $post->content );
        $stmt->bindValue(':visibility', $post->visibility);
        $stmt->bindValue(':slug', $post->slug);

        $stmt->execute;

        return $db->lastInsertId();
    }

    /**
     * @param string $slug
     * @param int $id
     * @return array
     */
    public function getAll($slug = '', $id = 0)
    {
        $sql = 'SELECT `id`, `title`, `content`, `created_at`, `updated_at`, `visibility`, `slug` FROM `posts`';

        $sqlCond = [];
        if (!empty($slug)) {
            $sqlCond[] = '`slug` = :slug\n';
        }
        if (!empty($id)) {
            $sqlCond[] = '`id` = :id\n';
        }

        if (count($sqlCond) > 0) {
            $sql .= 'WHERE ' . implode(" AND", $sqlCond);
        }

        $stmt = $this->getDB()->prepare($sql);
        if (!empty($slug)) {
            $stmt->bindValue(':slug', $slug);
        }
        if (!empty($id)) {
            $stmt->bindValue(':id', $id);
        }
        $stmt->execute();

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        // Transform fetched data to Post entities
        $posts = [];
        foreach ($rows as $row) {
            $posts[] = new Post($row);
        }
        return $posts;
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