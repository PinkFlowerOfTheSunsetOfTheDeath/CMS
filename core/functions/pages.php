<?php

use \App\Repositories\PageRepository;

/**
 * Get Visible pages from database
 * @return \App\Entities\Page[] - Array of pages found
 */
function get_pages() {
    $pageRepository = new PageRepository();
    $pages = $pageRepository->getAll('', 0, 1);
    return $pages;
}