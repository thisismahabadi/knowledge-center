<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ArticleCategory;

class ArticleCategorySeeder extends Seeder
{
    /**
     * The article category record to seed.
     *
     * @var int
     */
    CONST ARTICLE_CATEGORY_RECORD_TO_SEED = 1000;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ArticleCategory::factory(self::ARTICLE_CATEGORY_RECORD_TO_SEED)->create();
    }
}
