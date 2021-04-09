<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleView;
use Illuminate\Http\Request;
use App\Models\ArticleRating;
use Illuminate\Http\Response;
use App\Models\ArticleCategory;
use App\Http\Requests\RateRequest;
use App\Http\Requests\ArticlesListRequest;
use App\Http\Requests\CreateArticleRequest;

class ArticleController extends Controller
{
    /**
     * Rate an article.
     *
     * @param int $articleId
     * @param \App\Http\Requests\RateRequest $request
     *
     * @see \App\Models\ArticleRating::isEligible($ipAddress)
     * @see \App\Models\ArticleRating::hasRated(int $articleId, $ipAddress)
     *
     * @return object
     */
    public function rate(int $articleId, RateRequest $request): object
    {
        try {
            $article = Article::find($articleId);

            if (! $article) {
                throw new \Exception('Article has not found.');
            }

            (new ArticleRating)->isEligible(\Request::ip());
            (new ArticleRating)->hasRated($articleId, \Request::ip());

        	$rate = ArticleRating::create([
    	        'article_id' => $articleId,
    	        'score' => $request->score,
    	        'ip_address' => \Request::ip(),
        	]);

            return $this->setResponse(self::SUCCESS, $rate, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->setResponse(self::ERROR, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create a new article.
     *
     * @param \App\Http\Requests\CreateArticleRequest $request
     *
     * @see \App\Models\ArticleCategory::assignCategory(array $categories, int $articleId)
     *
     * @return object
     */
    public function create(CreateArticleRequest $request): object
    {
        try {
        	$article = Article::create([
    	        'title' => $request->title,
    	        'body' => $request->body,
        	]);

            (new ArticleCategory)->assignCategory($request->categories, $article->id);

            return $this->setResponse(self::SUCCESS, $article, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->setResponse(self::ERROR, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get the list of articles.
     *
     * @param \App\Http\Requests\ArticlesListRequest $request
     *
     * @see \App\Models\Article
     *
     * @return object
     */
    public function get(ArticlesListRequest $request): object
    {
        try {
            $articles = (new Article)->init()
                ->filterByCategories($request->categories)
                ->filterByCreationDate($request->date)
                ->sortByViews($request->sort, $request->view_date)
                ->sortByPopularity($request->sort)
                ->limit($request->limit)
                ->searchByTitleOrBody($request->search)
                ->fetch();

            return $this->setResponse(self::SUCCESS, $articles, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->setResponse(self::ERROR, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
        try {
    		$article = Article::find($articleId);

            if (! $article) {
                throw new \Exception('Article has not found.');
            }

            (new ArticleView)->logView($articleId, \Request::ip());

            return $this->setResponse(self::SUCCESS, $article, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->setResponse(self::ERROR, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
