<?php

namespace Database\Factories;

use App\Models\ArticleView;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleViewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ArticleView::class;

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
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'article_id' => $this->faker->numberBetween(self::ARTICLE_RECORD_COUNTS_FROM, self::ARTICLE_RECORD_COUNTS_TO),
            'ip_address' => $this->faker->ipv4,
        ];
    }
}
