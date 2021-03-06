<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleStoreTest extends TestCase
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
     * Test create an article without categories.
     *
     * @return void
     */
    public function testCreateArticleWithoutCategories(): void
    {
        $response = $this->postJson('/api/articles', [
                'title' => $this->article->title,
                'body' => $this->article->body,
            ]);

        $articleId = json_decode($response->getContent())->data->id;

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonPath('data.id', $articleId)
            ->assertJsonPath('data.title', $this->article->title)
            ->assertJsonPath('data.body', $this->article->body);
    }

    /**
     * Test create an article with categories.
     *
     * @return void
     */
    public function testCreateArticleWithCategories(): void
    {
        $category = Category::factory()->create();

        $response = $this->postJson('/api/articles', [
                'title' => $this->article->title,
                'body' => $this->article->body,
                'categories' => [$category->id],
            ]);

        $articleId = json_decode($response->getContent())->data->id;

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonPath('data.id', $articleId)
            ->assertJsonPath('data.title', $this->article->title)
            ->assertJsonPath('data.body', $this->article->body)
            ->assertJsonPath('data.categories.0.id', $category->id);
    }

    /**
     * Test create an article with unavailable category.
     *
     * @return void
     */
    public function testCreateArticleWithUnavailableCategory(): void
    {
        $response = $this->postJson('/api/articles', [
            'title' => $this->article->title,
            'body' => $this->article->body,
            'categories' => [11111111111],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
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

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
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

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
