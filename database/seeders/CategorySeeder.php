<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * The category record to seed.
     *
     * @var int
     */
    CONST CATEGORY_RECORD_TO_SEED = 10;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory(self::CATEGORY_RECORD_TO_SEED)->create();
    }
}
