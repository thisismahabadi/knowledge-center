<?php

namespace App\Services;

use App\Models\Article;
use App\Repositories\ArticleWeightedRatingRepository;

class ArticleWeightedRatingService
{
    /**
     * The repository data object.
     *
     * @var object
     */
    public $repositories;

    /**
     * This will inject dependencies required by article weighted rating service.
     *
     * @param \App\Repositories\ArticleWeightedRatingRepository $repository
     */
    public function __construct(ArticleWeightedRatingRepository $repository)
    {
        $this->repositories = $repository;
    }

    /**
     * Update rating of an article.
     *
     * @param \App\Models\Article $article
     *
     * @return float
     */
    public function calculate(Article $article): float
    {
        return $this->repositories
            ->calculateWeightedRating($article);
    }
}
