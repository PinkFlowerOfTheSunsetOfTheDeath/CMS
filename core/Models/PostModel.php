<?php

namespace App\Models;
use App\Helpers\Model;

class PostModel extends Model
{
    /**
     * Add new post to Database with validation
     * @param $data
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
}