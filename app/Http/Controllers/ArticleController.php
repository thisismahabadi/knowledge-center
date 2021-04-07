<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleView;
use Illuminate\Http\Request;
use App\Models\ArticleRating;
use App\Models\ArticleCategories;

class ArticleController extends Controller
{
    /**
     * Rate an article.
     *
     * @param int $articleId
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return object
     */
    public function rate(int $articleId, Request $request)//: object
    {
    	$rate = ArticleRating::where('ip_address', $request->ip());
    	$todayRates = $rate->whereDate('created_at', now())
    		->count();

    	if ($todayRates >= 10) {
			throw new \Exception('You just can rate the articles 10 times a day.');
    	}

    	$rate = $rate->where('article_id', $articleId);

		if (! $rate->count()) {
	    	return ArticleRating::create([
		        'article_id' => $articleId,
		        'score' => $request->score,
		        'ip_address' => $request->ip(),
	    	]);
		}

		throw new \Exception('You just can rate to an article once.');
    }

    /**
     * Create a new article.
     *
     * @param Request $request
     *
     * @return object
     */
    public function create(Request $request): object
    {
    	$article = Article::create([
	        'title' => $request->title,
	        'body' => $request->body,
    	]);

    	if ($request->categories) {
	    	foreach ($request->categories as $category) {
	    		ArticleCategories::create([
			        'article_id' => $article->id,
			        'category_id' => $category,
	    		]);
	    	}
    	}

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
     * @return object
     */
    public function show(int $articleId): object
    {
    	$view = ArticleView::where('article_id', $articleId)
    		->where('ip_address', \Request::ip())
    		->count();

		if (! $view) {
	    	ArticleView::create([
		        'article_id' => $articleId,
		        'ip_address' => \Request::ip(),
	    	]);
		}

		return Article::find($articleId);
    }
}
