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
     * @param int $articleId
     * @param \App\Http\Requests\ArticleRatingRequest $request
     * @param \App\Services\ArticleRatingService $service
     *
     * @see \App\Services\ArticleRatingService::rate(int $articleId, object $request)
     *
     * @return object
     */
    public function rate(int $articleId, ArticleRatingRequest $request, ArticleRatingService $service): object
    {
        $request->merge(['ip_address' => \Request::ip()]);

        $rate = $service->rate($articleId, $request);

        return new RateResource($rate);
    }
}
