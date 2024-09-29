<?php
namespace App\Services\Adapters\NewsAdapters;

use App\Services\Adapters\BaseNewsApiAdapter;
use Carbon\Carbon;

class GuardianApiAdapter extends BaseNewsApiAdapter
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = 'https://content.guardianapis.com/search';
        $this->apiKey = config('app.guardian_api_key');
    }
    protected function getArticles(): array
    {
        $allArticles = [];
        $page = 1;
        $maxPages = 10;

        do {
            $response = $this->makeRequest([
                'api-key' => $this->apiKey,
                'edition' => 'us',
                'show-fields' => 'byline,trailText,thumbnail',
                'order-by' => 'newest',
                'show-related' => 'true',
                'page' => $page,
            ]);

            if ($response->successful()) {
                $articles = $response->json()['response']['results'];

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
            'title' => $article['webTitle'],
            'description' => $article['fields']['trailText'],
            'category' => $article['sectionName'],
            'source' => 'The Guardian',
            'author' => $article['fields']['byline'] ?? null,
            'url' => $article['webUrl'],
            'published_at' => Carbon::parse($article['webPublicationDate'])->format('Y-m-d H:i:s'),
        ];
    }
}
