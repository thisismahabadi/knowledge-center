<?php

namespace Database\Seeders;

use App\Models\ArticleView;
use Illuminate\Database\Seeder;

class ArticleViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ArticleView::factory(100000)->create();
    }
}
