<?php

namespace Database\Factories;

use App\Models\Article;
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
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'article_id' => Article::factory(),
            'score' => $this->faker->numberBetween(1, 5),
            'ip_address' => $this->faker->ipv4,
        ];
    }
}
