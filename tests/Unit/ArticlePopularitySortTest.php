<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Article;
use App\Models\ArticleRating;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticlePopularitySortTest extends TestCase
{
    use RefreshDatabase;

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
        $firstArticle = Article::factory()->create();
        $secondArticle = Article::factory()->create();

        ArticleRating::factory()->create([
            'article_id' => $secondArticle->id,
            'score' => 5,
        ]);

		for ($i = 0; $i < 4; $i++) { 
            ArticleRating::factory()->create([
                'article_id' => $firstArticle->id,
                'score' => 5,
            ]);
		}

    	$articles = (new Article)->init()
	    	->sortByPopularity('popularity')
	    	->fetch();

    	$numbers = [];
    	foreach ($articles as $article) {
    		$numbers[] = $article->id;
    	}

        $this->assertGreaterThan($numbers[0], $numbers[1]);
    }
}
