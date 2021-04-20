<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleRatingTest extends TestCase
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

        $response = $this->postJson('/api/ratings', [
                'article_id' => $articleId,
                'score' => 5,
            ]);

        $rate = json_decode($response->getContent())->data;

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonPath('data.id', $rate->id)
            ->assertJsonPath('data.article_id', $rate->article_id)
            ->assertJsonPath('data.score', $rate->score);
    }

    /**
     * Test rate an article multiple times.
     *
     * @return void
     */
    public function testRateArticleMultipleTimes(): void
    {
        $this->postJson('/api/ratings', [
            'article_id' => $this->article,
            'score' => 5,
        ]);

        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->postJson("/api/ratings", [
                'article_id' => $this->article,
                'score' => 5,
            ]);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->assertExactJson([
                'data' => ['error_message' => 'You just can rate to an article once.']
            ]);
    }

    /**
     * Test rate an article without required fields.
     *
     * @return void
     */
    public function testRateArticleWithoutRequiredFields(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->postJson("/api/ratings");

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test rate an article with false score.
     *
     * @return void
     */
    public function testRateArticleWithFalseScore(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->postJson("/api/ratings", [
                'article_id' => $this->article,
                'score' => 3.5,
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test rate a unkown article.
     *
     * @return void
     */
    public function testRateUnknownArticle(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->postJson('/api/ratings', [
                'article_id' => 11111111111,
                'score' => 1,
            ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
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

            $this->postJson("/api/ratings", [
                'article_id' => $article->id,
                'score' => 3,
            ]);
        }

        $article = Article::factory()->create();

        $response = $this->postJson("/api/ratings", [
            'article_id' => $article->id,
            'score' => 2,
        ]);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->assertExactJson([
                'data' => ['error_message' => 'You just can rate 10 articles per day.']
            ]);
    }
}
