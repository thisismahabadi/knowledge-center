<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleRating;
use App\Exceptions\RatingException;

class ArticleRateService
{
    /**
     * The rate limit per day.
     *
     * @var int
     */
    CONST RATE_LIMIT_PER_DAY = 10;

    /**
     * Check if an user is eligible to rate today or not.
     *
     * @param $ipAddress
     *
     * @throws \Exception\RatingException
     *
     * @return bool
     */
    public function isDailyLimitRemained($ipAddress): bool
    {
        $todayRates = ArticleRating::where('ip_address', $ipAddress)
            ->whereDate('created_at', date('Y-m-d'))
            ->count();

        if ($todayRates >= self::RATE_LIMIT_PER_DAY) {
            throw RatingException::dailyLimitExceeded(self::RATE_LIMIT_PER_DAY);
        }

        return true;
    }

    /**
     * Check if an user has rated to the specified article or not.
     *
     * @param int $articleId
     * @param $ipAddress
     *
     * @throws \Exception\RatingException
     *
     * @return bool
     */
    public function hasRated(int $articleId, $ipAddress): bool
    {
        $rate = ArticleRating::where('ip_address', $ipAddress)
            ->where('article_id', $articleId)
            ->exists();

        if ($rate) {
            throw RatingException::hasRated();
        }

        return false;
    }

    /**
     * Rate an article.
     *
     * @param int $articleId
     * @param object $request
     *
     * @return object
     */
    public function rate(int $articleId, object $request): object
    {
        $article = Article::findOrFail($articleId);

        $hasRated = $this->hasRated($articleId, $request->ip_address);

        if (! $hasRated) {
            $this->isDailyLimitRemained($request->ip_address);
        }

        return $article->ratings()
            ->create([
                    'ip_address' => $request->ip_address,
                    'score' => $request->score,
                ]);
    }
}
