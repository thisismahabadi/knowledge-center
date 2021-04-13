<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Article object id.
     *
     * @var int
     */
    public $article;

    /**
     * Test initial setup.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->article = Article::factory()->make();
    }

    /**
     * Test create an article.
     *
     * @return void
     */
    public function testCreateArticle(): void
    {
        $response = $this->postJson('/api/articles', [
                'title' => $this->article->title,
                'body' => $this->article->body,
            ]);

        $response->assertStatus(201);
    }

    /**
     * Test create an article without required fields.
     *
     * @return void
     */
    public function testCreateArticleWithoutRequiredFields(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->post('/api/articles');

        $response->assertStatus(422);
    }

    /**
     * Test create an article with false validations.
     *
     * @return void
     */
    public function testCreateArticleWithFalseValidations(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->postJson('/api/articles', [
                'title[]' => 1,
                'body[]' => 2,
            ]);

        $response->assertStatus(422);
    }
}
