<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RateRequest;
use App\Services\ArticleViewService;
use App\Services\ArticleRateService;
use App\Services\ArticleListService;
use App\Http\Resources\RateResource;
use App\Services\ArticleStoreService;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\ArticlesListRequest;
use App\Http\Requests\CreateArticleRequest;

class ArticleController extends Controller
{
    /**
     * Rate an article.
     *
     * @param int $articleId
     * @param \App\Http\Requests\RateRequest $request
     * @param \App\Services\ArticleRateService $service
     *
     * @see \App\Services\ArticleRateService::rate(int $articleId, RateRequest $request)
     *
     * @return object
     */
    public function rate(int $articleId, RateRequest $request, ArticleRateService $service): object
    {
        $request->merge(['ip_address' => \Request::ip()]);

        $rate = $service->rate($articleId, $request);

        return new RateResource($rate);
    }

    /**
     * Create a new article.
     *
     * @param \App\Http\Requests\CreateArticleRequest $request
     * @param \App\Services\ArticleStoreService $service
     *
     * @see \App\Services\ArticleStoreService::create(CreateArticleRequest $request)
     *
     * @return object
     */
    public function create(CreateArticleRequest $request, ArticleStoreService $service): object
    {
        $article = $service->create($request);

        return new ArticleResource($article);
    }

    /**
     * Get the list of articles.
     *
     * @param \App\Http\Requests\ArticlesListRequest $request
     * @param \App\Services\ArticleListService $service
     *
     * @see \App\Services\ArticleListService::get(ArticlesListRequest $request)
     *
     * @return object
     */
    public function get(ArticlesListRequest $request, ArticleListService $service): object
    {
        $articles = $service->get($request);

        return ArticleResource::collection($articles);
    }

    /**
     * Show an article detail.
     *
     * @param int $articleId
     * @param \App\Services\ArticleViewService $service
     *
     * @see \App\Services\ArticleViewService::show(int $articleId)
     *
     * @return object
     */
    public function show(int $articleId, ArticleViewService $service): object
    {
        $article = $service->show($articleId);

        return new ArticleResource($article);
    }
}
