<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RateArticleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create new article and rate that article.
     *
     * @return void
     */
    public function testCreateNewArticleAndRateArticle(): void
    {
        $article = $this->postJson('/api/articles', [
                'title' => 'Test title',
                'body' => 'Test body detail',
            ]);

        $articleId = json_decode($article->getContent())->data->id;

        $response = $this->postJson("/api/articles/$articleId/rate", [
                'score' => 5,
            ]);

        $response->assertStatus(201);

        Article::find($articleId)->forceDelete();
    }

    /**
     * Test rate an article multiple times.
     *
     * @return void
     */
    public function testRateArticleMultipleTimes(): void
    {
        $article = Article::create([
            'title' => 'Test title',
            'body' => 'Test body',
        ]);

        $this->postJson("/api/articles/$article->id/rate", [
            'score' => 5,
        ]);

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->postJson("/api/articles/$article->id/rate", [
                'score' => 5,
            ]);

        $response->assertStatus(500);

        Article::find($article->id)->forceDelete();
    }

    /**
     * Test rate an article without required fields.
     *
     * @return void
     */
    public function testRateArticleWithoutRequiredFields(): void
    {
        $article = Article::create([
            'title' => 'Test title',
            'body' => 'Test body',
        ]);

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post("/api/articles/$article->id/rate");

        $response->assertStatus(422);

        Article::find($article->id)->forceDelete();
    }

    /**
     * Test rate an article with false score.
     *
     * @return void
     */
    public function testRateArticleWithFalseScore(): void
    {
        $article = Article::create([
            'title' => 'Test title',
            'body' => 'Test body',
        ]);

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->postJson("/api/articles/$article->id/rate", [
                'score' => 3.5,
            ]);

        $response->assertStatus(422);

        Article::find($article->id)->forceDelete();
    }

    /**
     * Test rate a unkown article.
     *
     * @return void
     */
    public function testRateUnknownArticle(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->postJson('/api/articles/111111111/rate', [
                'score' => 1,
            ]);

        $response->assertStatus(500);
    }

    /**
     * Test rate articles is not possible more than ten times a day.
     *
     * @return void
     */
    public function testRateArticlesIsNotPossibleTenTimesADay(): void
    {
        $articles = [];

        for ($i = 0; $i < 10; $i++) {
            $article = Article::create([
                'title' => 'Test title',
                'body' => 'Test body',
            ]);

            $articles[] = $article->id;

            $this->postJson("/api/articles/$article->id/rate", [
                'score' => 3,
            ]);
        }

        $response = $this->postJson("/api/articles/$articles[0]/rate", [
            'score' => 2,
        ]);

        $response->assertStatus(500);

        Article::whereIn('id', $articles)->forceDelete();
    }
}
