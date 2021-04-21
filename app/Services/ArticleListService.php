<?php

namespace App\Services;

use App\Models\Article;
use App\Repositories\ArticleListRepository;

class ArticleListService
{
    /**
     * The repository data object.
     *
     * @var object
     */
    public $repositories;

    /**
     * This will inject dependencies required by article list service.
     *
     * @param \App\Repositories\ArticleListRepository $repository
     */
    public function __construct(ArticleListRepository $repository)
    {
        $this->repositories = $repository;
    }

    /**
     * Get the list of articles.
     *
     * @param object $request
     *
     * @return object
     */
    public function get(object $request): object
    {
        return $this->repositories
            ->filterByCategories($request->categories)
            ->filterByCreationDate($request->date)
            ->sortByViews($request->sort, $request->view_date)
            ->sortByPopularity($request->sort)
            ->searchByTitleOrBody($request->search)
            ->fetch($request->limit);
    }
}
