<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Article;
use App\Models\ArticleRating;

class ArticlePopularitySortTest extends TestCase
{
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
    	$firstArticle = Article::create([
    		'title' => 'First title',
    		'body' => 'First body',
		]);

    	$secondArticle = Article::create([
    		'title' => 'Second title',
    		'body' => 'Second body',
		]);

		ArticleRating::create([
	        'article_id' => $secondArticle->id,
	        'score' => 5,
	        'ip_address' => long2ip(mt_rand()),
    	]);

		for ($i = 0; $i < 4; $i++) { 
			ArticleRating::create([
		        'article_id' => $firstArticle->id,
		        'score' => 5,
		        'ip_address' => long2ip(mt_rand()),
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

        Article::whereIn('id', $numbers)->forceDelete();
    }
}
