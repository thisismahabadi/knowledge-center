<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * The article record to seed.
     *
     * @var int
     */
    CONST ARTICLE_RECORD_TO_SEED = 1000;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Article::factory(self::ARTICLE_RECORD_TO_SEED)
            ->create();
    }
}
