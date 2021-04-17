<?php

namespace Database\Seeders;

use App\Models\ArticleRating;
use Illuminate\Database\Seeder;

class ArticleRatingSeeder extends Seeder
{
    /**
     * The article rating record to seed.
     *
     * @var int
     */
    CONST ARTICLE_RATING_RECORD_TO_SEED = 10000;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ArticleRating::factory(self::ARTICLE_RATING_RECORD_TO_SEED)
            ->create();
    }
}
