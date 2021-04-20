<?php

namespace App\Repositories;

use App\Models\ArticleView;

class ArticleViewRepository
{
    /**
     * Check if an ip address has viewed an article.
     *
     * @param int $articleId
     * @param $ipAddress
     *
     * @return int
     */
    public function hasViewed(int $articleId, $ipAddress): int
    {
        $view = ArticleView::where('article_id', $articleId)
            ->where('ip_address', $ipAddress)
            ->count();

        return $view;
    }
}
