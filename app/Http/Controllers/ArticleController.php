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
     * @see \App\Services\ArticleStoreService::create(object $request)
     *
     * @return object
     */
    public function create(ArticleStoreRequest $request, ArticleStoreService $service): object
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
     * @see \App\Services\ArticleListService::get(object $request)
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
