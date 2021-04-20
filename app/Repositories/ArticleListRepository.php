<?php

namespace App\Repositories;

use App\Models\Article;

class ArticleListRepository
{
    /**
     * The article data object.
     *
     * @var object
     */
    public $article;

    /**
     * The default page limit size.
     *
     * @var int
     */
    CONST DEFAULT_PAGE_LIMIT_SIZE = 10;

    /**
     * The sort by view key.
     *
     * @var string
     */
    CONST SORT_BY_VIEW = 'view';

    /**
     * The sort by popularity key.
     *
     * @var string
     */
    CONST SORT_BY_POPULARITY = 'popularity';

    /**
     * Initiate a new object if nothing passed.
     *
     * @param \App\Models\Article $article
     *
     * @return object
     */
    public function init(Article $article): object
    {
        $this->article = $article->with('categories:id,title');

        return $this;
    }

    /**
     * Filter articles by categories.
     *
     * @param array|null $categories
     *
     * @return object
     */
    public function filterByCategories(?array $categories): object
    {
        if ($categories) {
            $this->article = $this->article
                ->whereHas('categories', function($query) use($categories) {
                    $query->whereIn('category_id', $categories);
                });
        }

        return $this;
    }

    /**
     * Filter articles by their creation date.
     *
     * @param array|null $date
     *
     * @return object
     */
    public function filterByCreationDate(?array $date): object
    {
        if ($date) {
            $start = $date['start'] ?? date('Y-m-d', strtotime(date('Y-m-d', time()) . ' - 365 day'));
            $end = $date['end'] ?? now();

            $this->article = $this->article
                ->whereBetween('articles.created_at', [$start, $end]);
        }

        return $this;
    }

    /**
     * Sort articles by their view numbers with view date.
     *
     * @param string|null $viewDate
     *
     * @return object
     */
    public function sortByViewsWithViewDate(?string $viewDate): object
    {
        if ($viewDate) {
            $this->article = $this->article
                ->whereHas('articleView', function($query) use($viewDate) {
                    $query->whereDate('created_at', $viewDate);
                });
        }

        return $this;
    }

    /**
     * Sort articles by their view numbers.
     *
     * @param array|null $sort
     *
     * @see \App\Services\ArticleListService::sortByViewsWithViewDate(?string $viewDate)
     *
     * @return object
     */
    public function sortByViews(?array $sort): object
    {
        if (isset($sort['type']) && $sort['type'] === self::SORT_BY_VIEW) {
            if (isset($sort['view_date'])) {
                $this->sortByViewsWithViewDate($sort['view_date']);
            }

            $this->article = $this->article
                ->orderBy('articles.view_count', 'desc');
        }

        return $this;
    }

    /**
     * Sort articles based on their ratings and popularity.
     *
     * @param array|null $sort
     *
     * @return object
     */
    public function sortByPopularity(?array $sort): object
    {
        if (isset($sort['type']) && $sort['type'] === self::SORT_BY_POPULARITY) {
            $this->article = $this->article
                ->orderBy('articles.rating', 'desc');
        }

        return $this;
    }

    /**
     * Search in the article's title or body.
     *
     * @param string|null $search
     *
     * @return object
     */
    public function searchByTitleOrBody(?string $search): object
    {
        if ($search) {
            $this->article = $this->article
                ->whereRaw("MATCH(title, body) AGAINST('$search')");
        }

        return $this;
    }

    /**
     * Fetch the articles result object.
     *
     * @param int|null $limitSize
     *
     * @return object
     */
    public function fetch(int $limitSize = null): object
    {
        return $this->article
            ->limit($limitSize ?? self::DEFAULT_PAGE_LIMIT_SIZE)
            ->get();
    }
}
