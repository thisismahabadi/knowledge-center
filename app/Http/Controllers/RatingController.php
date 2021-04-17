<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ArticleRatingRequest;
use App\Http\Resources\RateResource;
use App\Services\ArticleRateService;

class RatingController extends Controller
{
    /**
     * Rate an article.
     *
     * @param int $articleId
     * @param \App\Http\Requests\ArticleRatingRequest $request
     * @param \App\Services\ArticleRateService $service
     *
     * @see \App\Services\ArticleRateService::rate(int $articleId, object $request)
     *
     * @return object
     */
    public function rate(int $articleId, ArticleRatingRequest $request, ArticleRateService $service): object
    {
        $request->merge(['ip_address' => \Request::ip()]);

        $rate = $service->rate($articleId, $request);

        return new RateResource($rate);
    }
}
