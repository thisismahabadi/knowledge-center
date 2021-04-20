<?php

namespace App\Repositories;

use App\Models\Article;

class ArticleWeightedRatingRepository
{
    /**
     * Update rating of an article.
     *
     * @param \App\Models\Article $article
     *
     * @return float
     */
    public function calculateWeightedRating(Article $article): float
    {
        $scores = $article->articleRating()
            ->select(['score', \DB::raw('COUNT(*) as count')])
            ->groupBy('score')
            ->pluck('count', 'score');

        return $scores->map(
                        fn (int $count, int $score): int => $count * $score
                    )
                    ->sum() / 100;
    }
}
