<?php

namespace Database\Seeders;

use App\Models\ArticleRating;
use Illuminate\Database\Seeder;

class ArticleRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ArticleRating::factory(10000)->create();
    }
}
