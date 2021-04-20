<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ArticleViewService;
use App\Services\ArticleListService;
use App\Services\ArticleStoreService;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\ArticlesListRequest;
use App\Http\Requests\ArticleStoreRequest;

class ArticleController extends Controller
{
    /**
     * Create a new article.
     *
     * @param \App\Http\Requests\ArticleStoreRequest $request
     * @param \App\Services\ArticleStoreService $service
     *
     * @see \App\Services\ArticleStoreService::storeArticleAndAttachCategories(object $request)
     *
     * @return object
     */
    public function store(ArticleStoreRequest $request, ArticleStoreService $service): object
    {
        $article = $service->storeArticleAndAttachCategories($request);

        return new ArticleResource($article);
    }

    /**
     * Get the list of articles.
     *
     * @param \App\Http\Requests\ArticlesListRequest $request
     * @param \App\Services\ArticleListService $service
     *
     * @see \App\Services\ArticleListService::get(object $request)
     *
     * @return object
     */
    public function index(ArticlesListRequest $request, ArticleListService $service): object
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
     * @see \App\Services\ArticleViewService::showArticleAndRegisterArticleView(int $articleId)
     *
     * @return object
     */
    public function show(int $articleId, ArticleViewService $service): object
    {
        $article = $service->showArticleAndRegisterArticleView($articleId);

        return new ArticleResource($article);
    }
}
