<?php

namespace Database\Factories;

use App\Models\ArticleCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ArticleCategory::class;

    /**
     * The article record counts from.
     *
     * @var int
     */
    CONST ARTICLE_RECORD_COUNTS_FROM = 1;

    /**
     * The article record counts to.
     *
     * @var int
     */
    CONST ARTICLE_RECORD_COUNTS_TO = 1000;

    /**
     * The categor record counts from.
     *
     * @var int
     */
    CONST CATEGORY_RECORD_COUNTS_FROM = 1;

    /**
     * The categor record counts to.
     *
     * @var int
     */
    CONST CATEGORY_RECORD_COUNTS_TO = 10;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'article_id' => $this->faker->numberBetween(self::ARTICLE_RECORD_COUNTS_FROM, self::ARTICLE_RECORD_COUNTS_TO),
            'category_id' => $this->faker->numberBetween(self::CATEGORY_RECORD_COUNTS_FROM, self::CATEGORY_RECORD_COUNTS_TO),
        ];
    }
}
