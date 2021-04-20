<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\ArticleRating;
use App\Exceptions\RatingException;

class ArticleRatingRepository
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
     * @param \App\Models\Article $article
     * @param $ipAddress
     *
     * @throws \Exception\RatingException
     *
     * @return bool
     */
    public function hasRated(Article $article, $ipAddress): bool
    {
        $ipRates = $article->articleRating()
            ->where('ip_address', $ipAddress)
            ->exists();

        if ($ipRates) {
            throw RatingException::hasRated();
        }

        return false;
    }

    /**
     * Rate an article.
     *
     * @param object $request
     *
     * @return object
     */
    public function rateArticle(object $request): object
    {
        $article = Article::findOrFail($request->article_id);

        $hasRated = $this->hasRated($article, $request->ip());

        if (! $hasRated) {
            $this->isDailyLimitRemained($request->ip());
        }

        return $article->articleRating()
            ->create([
                    'ip_address' => $request->ip(),
                    'score' => $request->score,
                ]);
    }

}
