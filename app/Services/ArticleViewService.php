<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleView;

class ArticleViewService
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

    /**
     * Show an article detail.
     *
     * @param int $articleId
     *
     * @return object
     */
    public function show(int $articleId): object
    {
        $article = Article::findOrFail($articleId);

        if (! $this->hasViewed($articleId, \Request::ip())) {
            $article->articleView()
                ->create([
                    'ip_address' => \Request::ip(),
                ]);
        }

        return $article;
    }
}
