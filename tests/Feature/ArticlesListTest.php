<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticlesListTest extends TestCase
{
    /**
     * Test that articles list are correct.
     *
     * @return void
     */
    public function testArticlesList()
    {
        $response = $this->get('/api/articles');

        $response->assertStatus(200)
            ->assertSee('id')
            ->assertSee('title')
            ->assertSee('body')
            ->assertSee('created_at')
            ->assertSee('updated_at')
            ->assertSee('deleted_at');
    }

    /**
     * Test that articles list filter by categories are correct.
     *
     * @return void
     */
    public function testArticlesListFilterByRightStructureCategories()
    {
        $response = $this->get('/api/articles?categories[]=1');

        $response->assertStatus(200);
    }

    /**
     * Test that articles list filter by categories are not correct if categories send without array structure.
     *
     * @return void
     */
    public function testArticlesListFilterByFalseStructureCategories()
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/articles?categories=1');

        $response->assertStatus(422);
    }

    /**
     * Test that articles list filter by creation date are correct.
     *
     * @return void
     */
    public function testArticlesListFilterByRightStructureCreationDate()
    {
        $response = $this->get('/api/articles?date[start]=2020-01-01');

        $response->assertStatus(200);
    }

    /**
     * Test that articles list filter by creation date are not correct if creation date send without array structure.
     *
     * @return void
     */
    public function testArticlesListFilterByFalseStructureCreationDate()
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
    public function testArticlesListSortByRightStructureView()
    {
        $response = $this->get('/api/articles?sort=view');

        $response->assertStatus(200);
    }

    /**
     * Test that articles list sort by view are not correct if view send without string structure.
     *
     * @return void
     */
    public function testArticlesListSortByFalseStructureView()
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/articles?sort[]=view');

        $response->assertStatus(422);
    }

    /**
     * Test that articles list limit are correct.
     *
     * @return void
     */
    public function testArticlesListLimitByRightStructure()
    {
        $response = $this->get('/api/articles?limit=10');

        $response->assertStatus(200);
    }

    /**
     * Test that articles list limit are not correct if limit send without integer structure.
     *
     * @return void
     */
    public function testArticlesListLimitByFalseStructure()
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
    public function testArticlesListSearchByRightStructure()
    {
        $response = $this->get('/api/articles?search=A');

        $response->assertStatus(200);
    }

    /**
     * Test that articles list search are not correct if search send without string structure.
     *
     * @return void
     */
    public function testArticlesListSearchByFalseStructure()
    {
        $response = $this->withHeaders(['Accept' => 'application/json'])
            ->get('/api/articles?search[]=1');

        $response->assertStatus(422);
    }
}
