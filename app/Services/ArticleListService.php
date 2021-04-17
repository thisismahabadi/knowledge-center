<?php

namespace App\Services;

use App\Models\Article;

class ArticleListService
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
    CONST DEFAULT_PAGE_LIMIT_SIZE = 100;

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
     * @param Article $article
     *
     * @return object
     */
    public function init(Article $article = null): object
    {
        if ($article) {
            $this->article = $article;

            return $this;
        }

        $this->article = new Article;

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
     * Sort articles based on given parameter.
     *
     * @param array|null $sort
     *
     * @return object
     */
    public function sort(?array $sort): object
    {
        switch (isset($sort['type'])) {
            case self::SORT_BY_VIEW:
                return $this->sortByViews(self::SORT_BY_VIEW, $sort['view_date'] ?? null);
                break;
            case self::SORT_BY_POPULARITY:
                return $this->sortByPopularity(self::SORT_BY_POPULARITY);
                break;

            default:
                return $this;
                break;
        }
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
                ->join('article_views', 'articles.id', '=', 'article_views.article_id')
                ->whereDate('article_views.created_at', $viewDate);
        }

        return $this;
    }

    /**
     * Sort articles by their view numbers.
     *
     * @param string|null $sort
     * @param string|null $viewDate
     *
     * @see App\Models\Article::sortByViewsWithViewDate(?string $viewDate)
     *
     * @return object
     */
    public function sortByViews(?string $sort, ?string $viewDate): object
    {
        $this->sortByViewsWithViewDate($viewDate);

        $this->article = $this->article
            ->join('article_views', 'articles.id', '=', 'article_views.article_id')
            ->groupBy('article_views.article_id')
            ->select([\DB::raw('COUNT(article_views.article_id) as total_views'), 'articles.*'])
            ->orderBy('total_views', 'desc');

        return $this;
    }

    /**
     * Sort articles based on their ratings and popularity.
     *
     * @param string|null $sort
     *
     * @return object
     */
    public function sortByPopularity(?string $sort): object
    {
        $this->article = $this->article
            ->join('article_ratings', 'articles.id', '=', 'article_ratings.article_id')
            ->groupBy('article_ratings.article_id')
            ->select([\DB::raw('SUM(article_ratings.score) / COUNT(article_ratings.id) as total_rates, COUNT(article_ratings.ip_address) as attendance_numbers'), 'articles.*'])
            ->orderBy('total_rates', 'desc')
            ->orderBy('attendance_numbers', 'desc');

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
            $search = '%' . $search . '%';

            $this->article = $this->article
                ->where('articles.title', 'LIKE', $search)
                ->orWhere('articles.body', 'LIKE', $search);
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
    public function fetch(?int $limitSize = self::DEFAULT_PAGE_LIMIT_SIZE): object
    {
        return $this->article
            ->limit($limitSize ?? self::DEFAULT_PAGE_LIMIT_SIZE)
            ->get();
    }

    /**
     * Get the list of articles.
     *
     * @param object $request
     *
     * @see \App\Models\Article
     *
     * @return object
     */
    public function get(object $request): object
    {
        $articles = $this->init()
            ->filterByCategories($request->categories)
            ->filterByCreationDate($request->date)
            ->sort($request->sort)
            ->searchByTitleOrBody($request->search)
            ->fetch($request->limit);

        return $articles;
    }
}
