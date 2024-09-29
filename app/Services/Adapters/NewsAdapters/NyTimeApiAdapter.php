<?php
namespace App\Services\Adapters\NewsAdapters;

use App\Services\Adapters\BaseNewsApiAdapter;
use Carbon\Carbon;

class NyTimeApiAdapter extends BaseNewsApiAdapter
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = 'https://api.nytimes.com/svc/topstories/v2/home.json';
        $this->apiKey = config('app.ny_times_api_key');
    }

    public function getArticles(): array
    {
        $response= $this->makeRequest([
            'api-key' => $this->apiKey,
        ]);

        if ($response->successful()) {
            return $this->transformResponse($response->json()['results']);
        }

        return [];
    }

    protected function transformArticle(array $article): array
    {
        return [
            'title' => $article['title'],
            'description' => $article['abstract'],
            'category' => $article['section'],
            'source' => 'The New York Times',
            'author' => $article['byline'],
            'url' => $article['url'],
            'published_at' => Carbon::parse($article['published_date'])->format('Y-m-d H:i:s'),
        ];
    }
}
