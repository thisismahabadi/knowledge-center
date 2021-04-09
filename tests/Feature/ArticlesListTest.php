<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticlesListTest extends TestCase
{
    /**
     * Test that articles list are correct.
     *
     * @return void
     */
    public function testArticlesList(): void
    {
        $article = Article::create([
            'title' => 'Test title',
            'body' => 'Test body',
        ]);

        $response = $this->get('/api/articles');

        $response->assertStatus(200)
            ->assertSee('id')
            ->assertSee('title')
            ->assertSee('body')
            ->assertSee('created_at')
            ->assertSee('updated_at')
            ->assertSee('deleted_at');

        Article::find($article->id)->forceDelete();
    }

    /**
     * Test that articles list filter by categories are correct.
     *
     * @return void
     */
    public function testArticlesListFilterByRightStructureCategories(): void
    {
        $category = Category::create([
            'title' => 'Anything',
        ]);

        $response = $this->get("/api/articles?categories[]=$category->id");

        $response->assertStatus(200);

        Category::find($category->id)->forceDelete();
    }

    /**
     * Test that articles list filter by categories are not correct if categories send without array structure.
     *
     * @return void
     */
    public function testArticlesListFilterByFalseStructureCategories(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get("/api/articles?categories=hello");

        $response->assertStatus(422);
    }

    /**
     * Test that articles list filter by creation date are correct.
     *
     * @return void
     */
    public function testArticlesListFilterByRightStructureCreationDate(): void
    {
        $response = $this->get('/api/articles?date[start]=2020-01-01');

        $response->assertStatus(200);
    }

    /**
     * Test that articles list filter by creation date are not correct if creation date send without array structure.
     *
     * @return void
     */
    public function testArticlesListFilterByFalseStructureCreationDate(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/articles?date=2020');

        $response->assertStatus(422);
    }

    /**
     * Test that articles list sort by views are correct.
     *
     * @return void
     */
    public function testArticlesListSortByRightStructureView(): void
    {
        $response = $this->get('/api/articles?sort=view');

        $response->assertStatus(200);
    }

    /**
     * Test that articles list sort by view are not correct if view send without string structure.
     *
     * @return void
     */
    public function testArticlesListSortByFalseStructureView(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/articles?sort[]=view');

        $response->assertStatus(422);
    }

    /**
     * Test that articles list sort by popularity are correct.
     *
     * @return void
     */
    public function testArticlesListPopularityByRightStructureView(): void
    {
        $response = $this->get('/api/articles?sort=popularity');

        $response->assertStatus(200);
    }

    /**
     * Test that articles list sort by popularity are not correct if popularity send without string structure.
     *
     * @return void
     */
    public function testArticlesListPopularityByFalseStructureView(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/articles?sort[]=popularity');

        $response->assertStatus(422);
    }

    /**
     * Test that articles list limit are correct.
     *
     * @return void
     */
    public function testArticlesListLimitByRightStructure(): void
    {
        $response = $this->get('/api/articles?limit=10');

        $response->assertStatus(200);
    }

    /**
     * Test that articles list limit are not correct if limit send without integer structure.
     *
     * @return void
     */
    public function testArticlesListLimitByFalseStructure(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/articles?limit=error');

        $response->assertStatus(422);
    }

    /**
     * Test that articles list search are correct.
     *
     * @return void
     */
    public function testArticlesListSearchByRightStructure(): void
    {
        $response = $this->get('/api/articles?search=A');

        $response->assertStatus(200);
    }

    /**
     * Test that articles list search are not correct if search send without string structure.
     *
     * @return void
     */
    public function testArticlesListSearchByFalseStructure(): void
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/articles?search[]=1');

        $response->assertStatus(422);
    }
}
