<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Services\Adapters\NewsAdapters\GuardianApiAdapter;
use App\Services\Adapters\NewsAdapters\NewsApiAdapter;
use App\Services\Adapters\NewsAdapters\NyTimeApiAdapter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FetchNewsArticlesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-news-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch News Articles';

    /**
     * Execute the console command.
     */
    public function handle():void
    {
        $adapters= [
            new NewsApiAdapter(),
            new GuardianApiAdapter(),
            new NyTimeApiAdapter(),
        ];

        foreach ($adapters as $adapter) {
            $articles = collect($adapter->fetchArticles());
            $articles->chunk(100)->each(function ($chunk) {
                DB::transaction(function () use ($chunk) {
                    Article::upsert($chunk->toArray(), ['url'], ['title', 'description', 'category', 'source', 'author', 'published_at']);
                });
            });
        }

    }
}
