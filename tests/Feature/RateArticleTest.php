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
     * Article object id.
     *
     * @var int
     */
    public $article;

    /**
     * Article mock object.
     *
     * @var object
     */
    public $articleMock;

    /**
     * Test initial setup.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $article = Article::factory()->create();

        $this->articleMock = Article::factory()->make();
        $this->article = $article->id;
    }

    /**
     * Test create new article and rate that article.
     *
     * @return void
     */
    public function testCreateNewArticleAndRateArticle(): void
    {
        $article = $this->postJson('/api/articles', [
                'title' => $this->articleMock->title,
                'body' => $this->articleMock->body,
            ]);

        $articleId = json_decode($article->getContent())->data->id;

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
    public function testRateArticleMultipleTimes(): void
    {
        $this->postJson("/api/articles/$this->article/rate", [
            'score' => 5,
        ]);

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->postJson("/api/articles/$this->article/rate", [
                'score' => 5,
            ]);

        $response->assertStatus(500);
    }

    /**
     * Test rate an article without required fields.
     *
     * @return void
     */
    public function testRateArticleWithoutRequiredFields(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post("/api/articles/$this->article/rate");

        $response->assertStatus(422);
    }

    /**
     * Test rate an article with false score.
     *
     * @return void
     */
    public function testRateArticleWithFalseScore(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->postJson("/api/articles/$this->article/rate", [
                'score' => 3.5,
            ]);

        $response->assertStatus(422);
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
            $article = Article::factory()->create();

            $articles[] = $article->id;

            $this->postJson("/api/articles/$article->id/rate", [
                'score' => 3,
            ]);
        }

        $response = $this->postJson("/api/articles/$articles[0]/rate", [
            'score' => 2,
        ]);

        $response->assertStatus(500);
    }
}
