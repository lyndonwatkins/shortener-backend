<?php

use Illuminate\Http\Request;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UrlMiddlewareTest extends TestCase
{
    /** @test */
    public function http_is_prepended_to_url()
    {
        $request = new Request;

        $request->replace([
            'url' => 'google.com'
        ]);

        $middleware = new \App\Http\Middleware\ModifiesUrlRequestData;

        $middleware->handle($request, function ($req) {
            $this->assertEquals('http://google.com', $req->url);
        });
    }

    /** @test */
    public function http_is_not_prepended_to_url_if_scheme_exists()
    {
        $request = new Request;

        $urls = [
            'ftp://www.google.com',
            'http://www.google.com',
            'https://www.google.com',
        ];

        foreach ($urls as $url) {
            $request->replace([
                'url' => $url
            ]);

            $middleware = new \App\Http\Middleware\ModifiesUrlRequestData;

            $middleware->handle($request, function ($req) use ($url) {
                $this->assertEquals($url, $req->url);
            });
        }
    }
}
