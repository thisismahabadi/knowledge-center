<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ArticleListService;
use Database\Factories\ArticleFactory;
use Database\Factories\ArticleRatingFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticlePopularitySortTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test initial setup.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $firstArticle = ArticleFactory::new()
            ->has(
                ArticleRatingFactory::new(['score' => 5])
                    ->count(1)
            )
            ->create();

        $secondArticle = ArticleFactory::new()
            ->has(
                ArticleRatingFactory::new(['score' => 5])
                    ->count(4)
            )
            ->create();
    }

    /**
     * Create two articles and rate with 5 score number the
     * first article four times more than the second one
     * and check if the first article is showed as first
     * record when sorting based on ratings.
     *
     * @return void
     */
    public function testWeightedRanking()
    {
    	$articles = (new ArticleListService)->init()
	    	->sortByPopularity(['type' => 'popularity'])
	    	->fetch();

        $this->assertGreaterThan($articles[1]->id, $articles[0]->id);
    }
}
