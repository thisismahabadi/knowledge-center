<?php

namespace App\Repositories;

use App\Models\Article;
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

    /**
     * Show an article detail.
     *
     * @param int $articleId
     *
     * @return object
     */
    public function showArticleAndRegisterArticleView(int $articleId): object
    {
        $article = Article::with('categories:id,title')
            ->findOrFail($articleId);

        if (! $this->hasViewed($articleId, \Request::ip())) {
            $article->articleView()
                ->create([
                    'ip_address' => \Request::ip(),
                ]);
        }

        return $article;
    }
}
