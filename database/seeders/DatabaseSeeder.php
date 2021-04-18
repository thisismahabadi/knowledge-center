<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Factories\ArticleFactory;
use Database\Factories\CategoryFactory;
use Database\Factories\ArticleViewFactory;
use Database\Factories\ArticleRatingFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        CategoryFactory::new()
            ->count(10)
            ->has(
                ArticleFactory::new()
                    ->count(100)
                    ->has(
                        ArticleRatingFactory::new()
                            ->count(10)
                    )
                    ->has(
                        ArticleViewFactory::new()
                            ->count(100)
                    )
            )->create();
    }
}
