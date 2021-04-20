<?php

namespace App\Services;

use App\Models\Article;
use App\Repositories\ArticleViewRepository;

class ArticleViewService
{
    /**
     * The repository data object.
     *
     * @var object
     */
    public $repositories;

    /**
     * This will inject dependencies required by article view service.
     *
     * @param \App\Repositories\ArticleViewRepository $repository
     */
    public function __construct(ArticleViewRepository $repository)
    {
        $this->repositories = $repository;
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

        if (! $this->repositories->hasViewed($articleId, \Request::ip())) {
            $article->articleView()
                ->create([
                    'ip_address' => \Request::ip(),
                ]);
        }

        return $article;
    }
}
