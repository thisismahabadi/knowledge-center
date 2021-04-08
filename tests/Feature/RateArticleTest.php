<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ArticleRating;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RateArticleTest extends TestCase
{
    /**
     * Test create new article and rate that article.
     *
     * @return void
     */
    public function testCreateNewArticleAndRateArticle()
    {
        $article = $this->postJson('/api/articles', [
                'title' => 'Test title',
                'body' => 'Test body detail',
            ]);

        $articleId = json_decode($article->getContent())->id;

        $response = $this->postJson("/api/articles/$articleId/rate", [
                'score' => 5,
            ]);

        $response->assertStatus(201);
    }

    /**
     * Test rate an article multiple times.
     *
     * @return void
     */
    public function testRateArticleMultipleTimes()
    {
        $this->postJson('/api/articles/1/rate', [
            'score' => 5,
        ]);

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->postJson('/api/articles/1/rate', [
                'score' => 5,
            ]);

        $response->assertStatus(500);
    }

    /**
     * Test rate an article without required fields.
     *
     * @return void
     */
    public function testRateArticleWithoutRequiredFields()
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post('/api/articles/1/rate');

        $response->assertStatus(422);
    }

    /**
     * Test rate an article with false score.
     *
     * @return void
     */
    public function testRateArticleWithFalseScore()
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->postJson('/api/articles/1/rate', [
                'score' => 3.5,
            ]);

        $response->assertStatus(422);
    }

    /**
     * Test rate articles is not possible more than ten times a day.
     *
     * @return void
     */
    public function testRateArticlesIsNotPossibleTenTimesADay()
    {
        for ($i=50; $i < 60; $i++) { 
            $this->postJson("/api/articles/$i/rate", [
                'score' => 3,
            ]);
        }

        $response = $this->postJson('/api/articles/100/rate', [
                'score' => 2,
            ]);

        $response->assertStatus(500);
    }
}
