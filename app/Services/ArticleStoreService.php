<?php

namespace App\Services;

use App\Repositories\ArticleStoreRepository;

class ArticleStoreService
{
    /**
     * The repository data object.
     *
     * @var object
     */
    public $repositories;

    /**
     * This will inject dependencies required by article store service.
     *
     * @param \App\Repositories\ArticleStoreRepository $repository
     */
    public function __construct(ArticleStoreRepository $repository)
    {
        $this->repositories = $repository;
    }

    /**
     * Create a new article.
     *
     * @param object $request
     *
     * @return object
     */
    public function storeArticleAndAttachCategories(object $request): object
    {
        return $this->repositories
            ->storeArticleAndAttachCategories($request);
    }
}
