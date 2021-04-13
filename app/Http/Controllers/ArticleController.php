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
     * @see \App\Models\ArticleRating::store(array $data)
     *
     * @return object
     */
    public function rate(int $articleId, RateRequest $request): object
    {
        $request->request->add(['article_id' => $articleId, 'ip_address' => \Request::ip()]);

        $rate = (new ArticleRating)->store($request->request->all());

        return $this->setResponse(self::SUCCESS, $rate, Response::HTTP_CREATED);
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
        $article = Article::create($request->all());

        (new ArticleCategory)->assignCategory($request->categories, $article->id);

        return $this->setResponse(self::SUCCESS, $article, Response::HTTP_CREATED);
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
        $articles = (new Article)->init()
            ->filterByCategories($request->categories)
            ->filterByCreationDate($request->date)
            ->sortByViews($request->sort, $request->view_date)
            ->sortByPopularity($request->sort)
            ->limit($request->limit)
            ->searchByTitleOrBody($request->search)
            ->fetch();

        return $this->setResponse(self::SUCCESS, $articles, Response::HTTP_OK);
    }

    /**
     * Show an article detail.
     *
     * @param int $articleId
     *
     * @see \App\Models\ArticleView::logView(int $articleId, $ipAddress)
     *
     * @return object
     */
    public function show(int $articleId): object
    {
        $article = Article::findOrFail($articleId);

        (new ArticleView)->logView($articleId, \Request::ip());

        return $this->setResponse(self::SUCCESS, $article, Response::HTTP_OK);
    }
}
