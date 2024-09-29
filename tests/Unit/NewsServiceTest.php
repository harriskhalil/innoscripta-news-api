<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use App\Http\Controllers\AuthController;
use App\Http\Requests\RegisterRequest;
use App\Services\Adapters\NewsAdapters\GuardianApiAdapter;
use App\Services\Adapters\NewsAdapters\NewsApiAdapter;

use App\Services\Adapters\NewsAdapters\NyTimeApiAdapter;
use Illuminate\Http\Request;
use Tests\TestCase;

class NewsServiceTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_news_api_adapter(): void
    {
        $newsApiAdapter= app()->make(NewsApiAdapter::class);
        $this->assertIsArray($newsApiAdapter->fetchArticles());
    }

    public function test_ny_times_adapter(): void
    {
        $newsApiAdapter= app()->make(NyTimeApiAdapter::class);
        $articles = $newsApiAdapter->fetchArticles();
        $this->assertIsArray($articles);
        $this->assertArrayHasKey('title', $articles[0]);
    }
    public function test_guardian_adapter(): void
    {
        $newsApiAdapter= app()->make(GuardianApiAdapter::class);
        $articles = $newsApiAdapter->fetchArticles();
        $this->assertIsArray($articles);
        $this->assertArrayHasKey('title', $articles[0]);
        $this->assertArrayHasKey('url', $articles[0]);
    }

//    public function test_controller()
//    {
//        $request= RegisterRequest::create('/api/auth/register', 'POST',[
//            'name'=>'Haris',
//            'email'=>'haris@gmail.com',
//            'password'=>'password',
//            'password_confirmation'=>'password'
//        ]);
//        $controller= new AuthController();
//
//        $response= $controller->register($request);
//
//        $this->assertEquals(200, $response->getStatusCode());
//        $this->assertEquals('SUCCESS',$response->getContent()->status);
//
//    }
}
