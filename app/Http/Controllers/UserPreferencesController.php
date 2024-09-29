<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserPreferencesController extends Controller
{
    public function setPreferences(Request $request)
    {
        $request->validate([
            'sources' => 'array',
            'sources.*' => 'exists:articles,source',
            'categories' => 'array',
            'categories.*' => 'exists:articles,category',
            'authors' => 'array',
            'authors.*' => 'exists:articles,author',
        ]);

        $user = Auth::user();
        $user->preferences = $request->only('sources', 'categories', 'authors');
        $user->save();

        return  $this->response('Preferences updated successfully.',$user);
    }

    public function getPreferences()
    {
        $user = Auth::user();
        return $this->response('Preferences retrieved.',json_decode($user->preferences));
    }

    public function fetchPersonalizedFeed(Request $request)
    {
        $user = Auth::user();
        $preferences = json_decode($user->preferences, true);;
        if (is_null($preferences)) {
            return $this->response('No preferences set, no articles found.', []);
        }
        $query = Article::query();

        if (!empty($preferences['sources']) || !empty($preferences['categories']) || !empty($preferences['authors'])) {
            $query->where(function($q) use ($preferences) {
                if (!empty($preferences['sources'])) {
                    $q->orWhereIn('source', $preferences['sources']);
                }

                if (!empty($preferences['categories'])) {
                    $q->orWhereIn('category', $preferences['categories']);
                }

                if (!empty($preferences['authors'])) {
                    $q->orWhereIn('author', $preferences['authors']);
                }
            });
        }
        $perPage = (int) $request->query('per_page', '10');

        $articles = $query->paginate($perPage);

        return $this->response('',$articles);
    }
}
