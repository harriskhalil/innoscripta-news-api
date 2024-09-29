<?php
namespace App\Services\Adapters\NewsAdapters;

use App\Services\Adapters\BaseNewsApiAdapter;
use Carbon\Carbon;

class NewsApiAdapter extends BaseNewsApiAdapter
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = 'https://newsapi.org/v2/top-headlines';
        $this->apiKey = config('app.news_api_key');
    }
    public function getArticles(): array
    {
        $allArticles = [];
        $page = 1;
        $maxPages = 10;

        do {
            $response = $this->makeRequest([
                'apiKey' => $this->apiKey,
                'country' => 'us',
                'page' => $page,
            ]);

            if ($response->successful()) {
                $articles = $response->json()['articles'];

                if (empty($articles)) {
                    break;
                }
                $allArticles = array_merge($allArticles, $this->transformResponse($articles));
            }

            $page++;
        } while ($page <= $maxPages);

        return $allArticles;
    }

    protected function transformArticle(array $article): array
    {
        return [
            'title' => $article['title'],
            'description' => $article['description'],
            'category' => 'general',
            'source' => $article['source']['name'],
            'author' => $article['author'],
            'url' => $article['url'],
            'published_at' => Carbon::parse($article['publishedAt'])->format('Y-m-d H:i:s'),
        ];
    }
}
