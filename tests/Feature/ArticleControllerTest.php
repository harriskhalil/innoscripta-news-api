<?php

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \App\Models\User;

uses(RefreshDatabase::class);
//beforeEach(function () {
//    // Clear the cache before each test
//    \Illuminate\Support\Facades\Cache::flush();
//});

it('returns paginated list of articles', function () {

    $user = User::factory()->create();
    $this->actingAs($user);

    Article::factory()->count(15)->create();


    $response = $this->get('/api/articles?per_page=10');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data',
            'links',
            'meta',
        ]);

    expect($response->json('data'))->toHaveCount(10);
});
it('returns a specific article', function () {

    $user = User::factory()->create();
    $this->actingAs($user);


    $article = Article::factory()->create();


    $response = $this->get('/api/article/' . $article->id);

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $article->id,
                'title' => $article->title,
                'description' => $article->description,
                'category' => $article->category,
                'source' => $article->source,
                'url' => $article->url,
                'published_at' => $article->published_at->format('Y-m-d H:i:s'),
                'author' => null,
            ],
        ]);
});

it('returns searched articles based on keyword', function () {

    $user = User::factory()->create();
    $this->actingAs($user);

    Article::factory()->create(['title' => 'Test Article 1', 'description' => 'This is a test article.']);
    Article::factory()->create(['title' => 'Another Article', 'description' => 'Different content here.']);

    $response = $this->get('/api/articles/search?keyword=Test');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data',
            'links',
            'meta',
        ])
        ->assertJsonFragment(['title' => 'Test Article 1']);

    $responseNoKeyword = $this->getJson('/api/articles/search');

    $responseNoKeyword->assertStatus(200)
        ->assertJsonStructure([
            'data',
            'links',
            'meta',
        ]);
});


it('returns articles based on published date', function () {

    $user = User::factory()->create();
    $this->actingAs($user);

    $article1 = Article::factory()->create(['published_at' => '2023-09-01']);
    $article2 = Article::factory()->create(['published_at' => '2023-09-02']);

    $response = $this->get('/api/articles/search?date=2023-09-01');

    $response->assertStatus(200)
        ->assertJsonFragment(['id' => $article1->id])
        ->assertJsonMissing(['id' => $article2->id]);
});


it('returns articles based on category and source', function () {

    $user = User::factory()->create();
    $this->actingAs($user);


    $article1 = Article::factory()->create(['category' => 'Tech', 'source' => 'Website A']);
    $article2 = Article::factory()->create(['category' => 'Health', 'source' => 'Website B']);


    $response = $this->get('/api/articles/search?category=Tech');


    $response->assertStatus(200)
        ->assertJsonFragment(['id' => $article1->id])
        ->assertJsonMissing(['id' => $article2->id]);


    $responseSource = $this->getJson('/api/articles/search?source=Website A');


    $responseSource->assertStatus(200)
        ->assertJsonFragment(['id' => $article1->id])
        ->assertJsonMissing(['id' => $article2->id]);
});
