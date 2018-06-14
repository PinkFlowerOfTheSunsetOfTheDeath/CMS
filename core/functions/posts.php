<?php

use \App\Repositories\PostRepository;

function get_posts(array $where = []) {
    $postRepository = new PostRepository();
    $posts = $postRepository->getWhere($where);
    dump($posts);
    return $posts;
}
