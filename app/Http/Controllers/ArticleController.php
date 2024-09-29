<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $executed = RateLimiter::attempt(
            'get-articles:' . auth()->user()->id,
            $perMinute = 5,
            function() use ($request, &$articles) {
                $perPage =(int) $request->query('per_page', '10');
                $cacheKey = 'articles_page_' . $request->query('page', '10') . '_per_page_' . $perPage;

                $articles = Cache::remember($cacheKey, 60, function() use ($perPage) {
                    return Article::paginate($perPage);
                });
                return ArticleResource::collection($articles);
            }
        );

        if (! $executed) {
            return $this->response('Too many messages sent!');
        }

        return $executed;
    }

    public function show(Article $article)
    {
        return new ArticleResource($article);
    }

    public function search(Request $request)
    {
        $executed = RateLimiter::attempt(
            'search-articles:'.auth()->user()->id,
            $perMinute = 5,
            function() use ($request) {
                $queryParams = $request->all();
                $cacheKey = 'articles_search_' . hash('sha256', json_encode($queryParams));

                $articles = Cache::remember($cacheKey, 60, function() use ($request) {
                    $query = Article::query();

                    if ($request->filled('keyword')) {
                        $query->where('title', 'like', '%' . $request->input('keyword') . '%')
                            ->orWhere('description', 'like', '%' . $request->input('keyword') . '%');
                    }

                    if ($request->filled('date')) {
                        $query->whereDate('published_at', $request->input('date'));
                    }

                    if ($request->filled('category')) {
                        $query->where('category', $request->input('category'));
                    }

                    if ($request->filled('source')) {
                        $query->where('source', $request->input('source'));
                    }

                    return $query->paginate(10);
                });

                return ArticleResource::collection($articles);
            }
        );

        if (! $executed) {
            return $this->response('Too many requests sent!');
        }

        return $executed;
    }
}
