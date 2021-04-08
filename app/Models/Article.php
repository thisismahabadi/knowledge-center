<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'body',
    ];

    /**
     * The article data object.
     *
     * @var object
     */
    public $article;

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

        $this->article = new self;

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
                ->join('article_categories', 'articles.id', '=', 'article_categories.article_id')
                ->whereIn('article_categories.category_id', $categories);
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
     * Sort articles by their view numbers.
     *
     * @param string|null $sort
     * @param string|null $viewDate
     *
     * @return object
     */
    public function sortByViews(?string $sort, ?string $viewDate): object
    {
        if ($sort) {
            switch ($sort) {
                case 'view':
                    $this->article = $this->article
                        ->join('article_views', 'articles.id', '=', 'article_views.article_id')
                        ->groupBy('articles.id')
                        ->select([\DB::raw('COUNT(articles.id) as total_views'), 'articles.*'])
                        ->orderBy('total_views', 'desc');
                    break;

                default:
                    break;
            }
        }

        if ($viewDate) {
            $this->article = $this->article
                ->whereDate('articles.created_at', '>=', $viewDate);
        }

        return $this;
    }

    /**
     * Limit articles and show records with specified size.
     *
     * @param int|null $limitSize
     *
     * @return object
     */
    public function limit(?int $limitSize): object
    {
        if ($limitSize) {
            $this->article = $this->article
                ->take($limitSize);
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
                ->where('articles.title', 'LIKE', '%' . $search . '%')
                ->orWhere('articles.body', 'LIKE', '%' . $search . '%');
        }

        return $this;
    }

    /**
     * Fetch the articles result object.
     *
     * @return object
     */
    public function fetch()
    {
        return $this->article
            ->get();
    }
}
