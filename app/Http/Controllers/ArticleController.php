<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleView;
use Illuminate\Http\Request;
use App\Models\ArticleRating;
use App\Models\ArticleCategory;

class ArticleController extends Controller
{
    /**
     * Rate an article.
     *
     * @param int $articleId
     * @param Request $request
     *
     * @see \App\Models\ArticleRating::isEligible($ipAddress)
     * @see \App\Models\ArticleRating::hasRated(int $articleId, $ipAddress)
     *
     * @return object
     */
    public function rate(int $articleId, Request $request): object
    {
        (new ArticleRating)->isEligible(\Request::ip());
    	(new ArticleRating)->hasRated($articleId, \Request::ip());

    	return ArticleRating::create([
	        'article_id' => $articleId,
	        'score' => $request->score,
	        'ip_address' => \Request::ip(),
    	]);
    }

    /**
     * Create a new article.
     *
     * @param Request $request
     *
     * @see \App\Models\ArticleCategory::assignCategory(array $categories, int $articleId)
     *
     * @return object
     */
    public function create(Request $request): object
    {
    	$article = Article::create([
	        'title' => $request->title,
	        'body' => $request->body,
    	]);

        (new ArticleCategory)->assignCategory($request->categories ?? [], $article->id);

    	return $article;
    }

    /**
     * Get the list of articles.
     *
     * @param Request $request
     *
     * @return object
     */
    public function get(Request $request): object
    {
    	$article = new Article;

    	if ($request->categories) {
			$article = $article->join('article_categories', 'articles.id', '=', 'article_categories.article_id')
				->whereIn('article_categories.category_id', $request->categories);
    	}

    	if ($request->date) {
    		$article = $article->whereBetween('created_at', [$request->date['start'], $request->date['end']]);
    	}

    	if ($request->sort) {
    		switch ($request->sort) {
    			case 'view':
    				$article = $article->join('article_views', 'articles.id', '=', 'article_views.article_id')
    					->groupBy('articles.id')
						->whereDate('articles.created_at', '>=', $request->view_date)
						->select([\DB::raw('COUNT(articles.id) as total_views'), 'articles.*'])
						->orderBy('total_views', 'desc');
    				break;

    			default:
    				break;
    		}
    	}

    	if ($request->limit) {
    		$article = $article->limit($request->limit);
    	}

    	if ($request->search) {
    		$article = $article->where('title', 'LIKE', '%' . $request->search . '%')
    			->orWhere('body', 'LIKE', '%' . $request->search . '%');
    	}

		return $article->get();
    }

    /**
     * Show an article detail.
     *
     * @param int $articleId
     *
     * @see \App\Models\ArticleView::logView(int $articleId, $ipAddress)
     *
     * @throws \Exception
     *
     * @return object
     */
    public function show(int $articleId): object
    {
		$article = Article::find($articleId);

        if (! $article) {
            throw new \Exception('Article has not found.');
        }

        (new ArticleView)->logView($articleId, \Request::ip());

        return $article;
    }
}
