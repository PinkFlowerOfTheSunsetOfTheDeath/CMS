<?php
namespace App\Repositories;

use App\Entities\Post;
use App\Helpers\Repository;
class PostRepository extends Repository
{
    /**
     * Create a Post inside database
     * @param Post $post - Post entity containing data to insert in database
     * @return Post - Post entity containing recently inserted data with row's ID
     * @throws \PDOException - PDO Exception thrown if SQL statement encounters an error
     */
    public function create($post): Post
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

        $stmt->execute();

        $this->errorManagement($stmt);

        $post->id = $db->lastInsertId();
        return $post;
    }

    /**
     * @param string $slug
     * @param int $id
     * @param null $visible
     * @return array
     */
    public function getAll($slug = '', $id = 0, $visible = null)
    {
        $sql = 'SELECT `id`, `title`, `content`, `created_at`, `updated_at`, `visibility`, `slug` FROM `posts`';

        $sqlCond = [];
        if (!empty($slug)) {
            $sqlCond[] = '`slug` = :slug';
        }
        if (!empty($id)) {
            $sqlCond[] = '`id` = :id';
        }

        if (!is_null($visible)) {
            $sqlCond[] = '`visibility` = :visibility';
        }

        if (count($sqlCond) > 0) {
            $sql .= ' WHERE ' . implode(" AND", $sqlCond);
        }

        $stmt = $this->getDB()->prepare($sql);
        if (!empty($slug)) {
            $stmt->bindValue(':slug', $slug);
        }
        if (!empty($id)) {
            $stmt->bindValue(':id', $id);
        }

        if (!is_null($visible)) {
            $stmt->bindValue(':visibility', $visible);
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

    /**
     * Find a Post by slug
     * @param string $slug - slug of the article to find
     * @param visible - Visibility of the post to retrieve, if null, retrieve both
     * @return array|Post - Post if found, else empty array
     */
    public function getBySlug(string $slug, $visible = null)
    {
        $post = current($this->getAll($slug, 0, $visible));
        return $post;
    }

    /**
     * Get a Single Post by ID
     * @param int $id - ID of the Post to retrieve
     * @return array|Post - Post Entity from Database
     */
    public function getById(int $id)
    {
        $post = current($this->getAll('', $id));
        return $post;
    }

    /**
     * Delete a post by ID
     * @param int $id - ID of the post to delete
     * @return bool - True if post was deleted properly
     * @throws \PDOException - exception raised if error encountered in SQL statement
     */
    public function deleteById(int $id)
    {
        $sql = "DELETE from `posts`
        WHERE `id` = :id
        LIMIT 1";

        $stmt = $this->getDB()->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $this->errorManagement($stmt);
        return true;
    }

    /**
     * Update a given post entity in Database
     * @param Post $post - Post to update with current values
     * @return bool - True if post got updated properly, else false
     */
    public function update(Post $post): bool
    {
        $sql = '
          UPDATE `posts` 
          SET 
            `title` = :title,
            `content` = :content,
            `visibility` = :visibility,
            `slug` = :slug,
            `updated_at` = NOW()
          WHERE `id` = :id';
        $stmt = $this->getDB()->prepare($sql);
        // Bind values
        $stmt->bindValue(':title', $post->title);
        $stmt->bindValue(':content', $post->content);
        $stmt->bindValue(':visibility', $post->visibility);
        $stmt->bindValue(':slug', $post->slug);
        $stmt->bindValue(':id', $post->id);
        // Execute query
        $stmt->execute();

        // Manage errors
        $this->errorManagement($stmt);
        return true;
    }

    /**
     * Update post visibility on website, set it visible if not, and not visible if visible
     * @param $post
     * @return bool
     */
    public function updateVisibility($post)
    {
        $currentVisibility = $post->visibility;

        if ($currentVisibility == 1) {
            $newVisibilityBehavior = 0;
        } else {
            $newVisibilityBehavior = 1;
        }

        $sql = 'UPDATE `posts`
        SET `visibility` = :visibility
        WHERE `id` = :id';

        $stmt = $this->getDB()->prepare($sql);
        $stmt->bindValue(':visibility', $newVisibilityBehavior);
        $stmt->execute();

        // Manage errors
        $this->errorManagement($stmt);
        return true;

    }
}