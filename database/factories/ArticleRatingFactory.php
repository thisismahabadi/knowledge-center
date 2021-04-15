<?php

namespace Database\Factories;

use App\Models\ArticleRating;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleRatingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ArticleRating::class;

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
     * The score counts from.
     *
     * @var int
     */
    CONST SCORE_COUNTS_FROM = 1;

    /**
     * The score counts to.
     *
     * @var int
     */
    CONST SCORE_COUNTS_TO = 5;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'article_id' => $this->faker->numberBetween(self::ARTICLE_RECORD_COUNTS_FROM, self::ARTICLE_RECORD_COUNTS_TO),
            'score' => $this->faker->numberBetween(self::SCORE_COUNTS_FROM, self::SCORE_COUNTS_TO),
            'ip_address' => $this->faker->ipv4,
        ];
    }
}
