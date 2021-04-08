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
     * @see \App\Models\Article
     *
     * @return object
     */
    public function get(Request $request): object
    {
        return (new Article)->init()
            ->filterByCategories($request->categories)
            ->filterByCreationDate($request->date)
            ->sortByViews($request->sort, $request->view_date)
            ->limit($request->limit)
            ->searchByTitleOrBody($request->search)
            ->fetch();
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
