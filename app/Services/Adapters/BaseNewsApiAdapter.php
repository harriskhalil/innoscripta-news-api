<?php
namespace App\Services\Adapters;

use Illuminate\Support\Facades\Http;

abstract class BaseNewsApiAdapter implements NewsApisAdapterInterface
{
    protected $apiUrl;
    protected $apiKey;

    public function fetchArticles(): array
    {
        return $this->getArticles();
    }

    protected function getArticles(): array
    {
        return [];
    }

    protected function makeRequest($params)
    {
        return Http::retry(3, 100)->get($this->apiUrl, $params);
    }

    protected function transformResponse(array $articles): array
    {
        return collect($articles)->map(function ($article) {
            return $this->transformArticle($article);
        })->toArray();
    }

    abstract protected function transformArticle(array $article): array;
}
