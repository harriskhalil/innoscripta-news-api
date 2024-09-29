<?php
namespace App\Services\Adapters;

interface NewsApisAdapterInterface
{
    public function fetchArticles(): array;
}
