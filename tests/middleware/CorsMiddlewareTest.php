<?php

use App\Http\Middleware\Cors;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CorsMiddlewareTest extends TestCase
{

    /** @test */
    public function correct_headers_are_sent()
    {
        $request = Request::create('/', 'POST');

        $middleware = new Cors;

        $response = new Response;

        $response = $middleware->handle($request, function () use ($response) {
            return $response;
        });

        $this->assertEquals($response->headers->get('Access-Control-Allow-Origin'), '*');
        $this->assertEquals($response->headers->get('Access-Control-Allow-Methods'), 'HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $this->assertEquals($response->headers->get('Access-Control-Allow-Headers'), 'Content-Type');

    }

    /** @test */
    public function preflight_request_is_handled()
    {
        $request = Request::create('/', 'OPTIONS');

        $middleware = new Cors;

        $response = $middleware->handle($request, function () {});

        $this->assertEquals($response->headers->get('Access-Control-Allow-Origin'), '*');
        $this->assertEquals($response->headers->get('Access-Control-Allow-Methods'), 'HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $this->assertEquals($response->headers->get('Access-Control-Allow-Headers'), 'Content-Type');

        $this->assertEquals($response->getStatusCode(), 200);
    }

}