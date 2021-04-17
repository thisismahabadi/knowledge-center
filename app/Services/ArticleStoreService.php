<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Support\Facades\DB;

class ArticleStoreService
{
    /**
     * Create a new article.
     *
     * @param object $request
     *
     * @return object
     */
    public function create(object $request): object
    {
        try {
            DB::beginTransaction();

            $article = Article::create($request->all());

            $article->categories()
                ->attach($request->categories);

            DB::commit();

            return $article;
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
