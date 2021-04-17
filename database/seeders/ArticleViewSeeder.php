<?php

namespace Database\Seeders;

use Api\Models\Article;
use App\Models\ArticleView;
use Illuminate\Database\Seeder;

class ArticleViewSeeder extends Seeder
{
    /**
     * The article view record to seed.
     *
     * @var int
     */
    CONST ARTICLE_VIEW_RECORD_TO_SEED = 100000;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ArticleView::factory(self::ARTICLE_VIEW_RECORD_TO_SEED)
            ->create();
    }
}
