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
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'article_id' => $this->faker->numberBetween(1, 1000),
            'ip_address' => $this->faker->ipv4,
        ];
    }
}
