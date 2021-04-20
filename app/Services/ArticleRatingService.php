<?php

namespace App\Services;

use App\Models\Article;
use App\Repositories\ArticleRatingRepository;

class ArticleRatingService
{
    /**
     * The repository data object.
     *
     * @var object
     */
    public $repositories;

    /**
     * This will inject dependencies required by article rating service.
     *
     * @param \App\Repositories\ArticleRatingRepository $repository
     */
    public function __construct(ArticleRatingRepository $repository)
    {
        $this->repositories = $repository;
    }

    /**
     * Rate an article.
     *
     * @param object $request
     *
     * @return object
     */
    public function rateArticle(object $request): object
    {
        return $this->repositories
            ->rateArticle($request);
    }
}
