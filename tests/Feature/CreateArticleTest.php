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
     * Test create an article.
     *
     * @return void
     */
    public function testCreateArticle(): void
    {
        $response = $this->postJson('/api/articles', [
                'title' => 'Test title',
                'body' => 'Test body detail',
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
