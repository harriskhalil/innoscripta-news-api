<?php
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use \App\Models\User;

uses(RefreshDatabase::class);


it('gets user preferences', function () {
    $user = User::factory()->create([
        'preferences' => json_encode([
            'sources' => ['Source A', 'Source B'],
            'categories' => ['Category A', 'Category B'],
            'authors' => ['Author A', 'Author B'],
        ]),
    ]);
    $this->actingAs($user);

    $response = $this->get('/api/preferences');

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Preferences retrieved.',
            'data' => [
                'sources' => ['Source A', 'Source B'],
                'categories' => ['Category A', 'Category B'],
                'authors' => ['Author A', 'Author B'],
            ],
        ]);
});


it('returns a message when preferences are null', function () {
    $user = User::factory()->create(['preferences' => null]);
    $this->actingAs($user);

    $response = $this->get('/api/personalized-feed');

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'No preferences set, no articles found.',
            'data' => [],
        ]);
});

it('returns articles based on user preferences', function () {

    $user = User::factory()->create([
        'preferences' => json_encode([
            'sources' => ['Source A', 'Source B'],
            'categories' => ['Category 1'],
            'authors' => ['Author X'],
        ]),
    ]);
    $this->actingAs($user);

    Article::factory()->create([
        'source' => 'Source A',
        'category'=>'Category 1',
        'author'=>'Author X',
    ]);
    Article::factory()->create([
        'source' => 'Source B',
        'category'=>'Category B',
        'author'=>'Author Y',
    ]);
    Article::factory()->create([
        'source' => 'Source C',
        'category'=>'Category C',
        'author'=>'Author Z',
    ]);

    $response = $this->get('/api/personalized-feed');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'source',
                        'category',
                        'author',
                        'url',
                        'published_at',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'first_page_url',
                'last_page_url',
                'links',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'total',
            ],
        ]);

    $this->assertCount(2, $response->json('data.data'));
});



it('can update user preferences', function () {

    $user = User::factory()->create();

    Article::factory()->create([
        'source' => 'ValidSource',
        'category' => 'ValidCategory',
        'author' => 'ValidAuthor'
    ]);


    $this->actingAs($user);

    $preferences = [
        'sources' => ['ValidSource'],
        'categories' => ['ValidCategory'],
        'authors' => ['ValidAuthor'],
    ];

    $response = $this->postJson('/api/preferences', $preferences);

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Preferences updated successfully.',
            'data' => [
                'preferences' => $preferences,
            ],
        ]);
});

it('fails validation if sources, categories, or authors do not exist in articles', function () {

    $user = User::factory()->create();

    $this->actingAs($user);

    $invalidPreferences = [
        'sources' => ['InvalidSource'],
        'categories' => ['InvalidCategory'],
        'authors' => ['InvalidAuthor'],
    ];

    $response = $this->postJson('/api/preferences', $invalidPreferences);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['sources.0', 'categories.0', 'authors.0']);
});
