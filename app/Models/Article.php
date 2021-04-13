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
     * Get the categories for the article.
     *
     * @return object
     */
    public function categories(): object
    {
        return $this->hasMany(ArticleCategory::class, 'article_id', 'id');
    }

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
                        ->groupBy('article_views.article_id')
                        ->select([\DB::raw('COUNT(article_views.article_id) as total_views'), 'articles.*'])
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
     * Sort articles based on their ratings and popularity.
     *
     * @param string|null $sort
     *
     * @return object
     */
    public function sortByPopularity(?string $sort): object
    {
        if ($sort) {
            switch ($sort) {
                case 'popularity':
                    $this->article = $this->article
                        ->join('article_ratings', 'articles.id', '=', 'article_ratings.article_id')
                        ->groupBy('article_ratings.article_id')
                        ->select([\DB::raw('SUM(article_ratings.score) / COUNT(article_ratings.id) as total_rates, COUNT(article_ratings.ip_address) as attendance_numbers'), 'articles.*'])
                        ->orderBy('total_rates', 'desc')
                        ->orderBy('attendance_numbers', 'desc');
                    break;

                default:
                    break;
            }
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
     * @param int|null $limitSize
     *
     * @return object
     */
    public function fetch(?int $limitSize = 100): object
    {
        return $this->article
            ->limit($limitSize)
            ->get();
    }
}
