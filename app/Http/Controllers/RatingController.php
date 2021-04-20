<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\RateResource;
use App\Services\ArticleRatingService;
use App\Http\Requests\ArticleRatingRequest;

class RatingController extends Controller
{
    /**
     * Rate an article.
     *
     * @param \App\Http\Requests\ArticleRatingRequest $request
     * @param \App\Services\ArticleRatingService $service
     *
     * @see \App\Services\ArticleRatingService::rateArticle(int $articleId, object $request)
     *
     * @return object
     */
    public function store(ArticleRatingRequest $request, ArticleRatingService $service): object
    {
        $rate = $service->rateArticle($request);

        return new RateResource($rate);
    }
}
