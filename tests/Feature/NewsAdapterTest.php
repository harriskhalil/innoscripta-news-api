<?php

use App\Services\Adapters\NewsAdapters\GuardianApiAdapter;
use App\Services\Adapters\NewsAdapters\NewsApiAdapter;
use App\Services\Adapters\NewsAdapters\NyTimeApiAdapter;
use App\Models\Article;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;


uses(RefreshDatabase::class);
it('fetches articles from NewsApiAdapter and upserts them into the database', function () {

    Http::fake([
        'https://newsapi.org/v2/top-headlines*' => Http::response([
            'articles' => [
                [
                    'title' => 'News Title 1',
                    'description' => 'News Description 1',
                    'source' => ['name' => 'News Source'],
                    'author' => 'News Author',
                    'url' => 'http://example.com/news1',
                    'publishedAt' => '2024-09-20T12:00:00Z',
                ],
            ],
        ], 200),
    ]);

    $adapter = new NewsApiAdapter();
    $articles = $adapter->fetchArticles();

    expect($articles)->toHaveCount(10);

    DB::transaction(function () use ($articles) {
        Article::upsert($articles, ['url'], ['title', 'description', 'category', 'source', 'author', 'published_at']);
    });

    $this->assertDatabaseHas('articles', [
        'title' => 'News Title 1',
        'source' => 'News Source',
        'url' => 'http://example.com/news1',
    ]);
});

it('fetches articles from GuardianApiAdapter and upserts them into the database', function () {

    Http::fake([
        'https://content.guardianapis.com/search*' => Http::response([
            'response' => [
                'results' => [
                    [
                        'webTitle' => 'Guardian Title 1',
                        'fields' => [
                            'trailText' => 'Guardian Description 1',
                            'byline' => 'Guardian Author',
                        ],
                        'sectionName' => 'World',
                        'webUrl' => 'http://example.com/guardian1',
                        'webPublicationDate' => '2024-09-20T12:00:00Z',
                    ],
                ],
            ],
        ], 200),
    ]);

    $adapter = new GuardianApiAdapter();
    $articles = $adapter->fetchArticles();

    expect($articles)->toHaveCount(10);

    DB::transaction(function () use ($articles) {
        Article::upsert($articles, ['url'], ['title', 'description', 'category', 'source', 'author', 'published_at']);
    });

    $this->assertDatabaseHas('articles', [
        'title' => 'Guardian Title 1',
        'source' => 'The Guardian',
        'url' => 'http://example.com/guardian1',
    ]);
});

it('fetches articles from NyTimeApiAdapter and upserts them into the database', function () {

    Http::fake([
        'https://api.nytimes.com/svc/topstories/v2/home.json*' => Http::response([
            'results' => [
                [
                    'title' => 'NYT Title 1',
                    'abstract' => 'NYT Description 1',
                    'section' => 'Politics',
                    'byline' => 'NYT Author',
                    'url' => 'http://example.com/nyt1',
                    'published_date' => '2024-09-20T12:00:00Z',
                ],
            ],
        ], 200),
    ]);

    $adapter = new NyTimeApiAdapter();
    $articles = $adapter->fetchArticles();

    expect($articles)->toHaveCount(1);


    DB::transaction(function () use ($articles) {
        Article::upsert($articles, ['url'], ['title', 'description', 'category', 'source', 'author', 'published_at']);
    });

    $this->assertDatabaseHas('articles', [
        'title' => 'NYT Title 1',
        'source' => 'The New York Times',
        'url' => 'http://example.com/nyt1',
    ]);
});

it('can handle multiple adapters and upsert articles from all sources', function () {

    Http::fake([
        'https://newsapi.org/v2/top-headlines*' => Http::response([
            'articles' => [
                [
                    'title' => 'News Title 1',
                    'description' => 'News Description 1',
                    'source' => ['name' => 'News Source'],
                    'author' => 'News Author',
                    'url' => 'http://example.com/news1',
                    'publishedAt' => '2024-09-20T12:00:00Z',
                ],
            ],
        ], 200),
        'https://content.guardianapis.com/search*' => Http::response([
            'response' => [
                'results' => [
                    [
                        'webTitle' => 'Guardian Title 1',
                        'fields' => [
                            'trailText' => 'Guardian Description 1',
                            'byline' => 'Guardian Author',
                        ],
                        'sectionName' => 'World',
                        'webUrl' => 'http://example.com/guardian1',
                        'webPublicationDate' => '2024-09-20T12:00:00Z',
                    ],
                ],
            ],
        ], 200),
        'https://api.nytimes.com/svc/topstories/v2/home.json*' => Http::response([
            'results' => [
                [
                    'title' => 'NYT Title 1',
                    'abstract' => 'NYT Description 1',
                    'section' => 'Politics',
                    'byline' => 'NYT Author',
                    'url' => 'http://example.com/nyt1',
                    'published_date' => '2024-09-20T12:00:00Z',
                ],
            ],
        ], 200),
    ]);

    $this->artisan('app:fetch-news-articles')->assertExitCode(0);

    $this->assertDatabaseHas('articles', [
        'title' => 'News Title 1',
        'source' => 'News Source',
        'url' => 'http://example.com/news1',
    ]);

    $this->assertDatabaseHas('articles', [
        'title' => 'Guardian Title 1',
        'source' => 'The Guardian',
        'url' => 'http://example.com/guardian1',
    ]);

    $this->assertDatabaseHas('articles', [
        'title' => 'NYT Title 1',
        'source' => 'The New York Times',
        'url' => 'http://example.com/nyt1',
    ]);
});
