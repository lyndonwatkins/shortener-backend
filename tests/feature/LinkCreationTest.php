<?php

use App\Link;
use Carbon\Carbon;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class LinkCreationTest extends TestCase
{
    /** @test */
    public function fails_if_no_url_given()
    {
        $response = $this->json('POST', '/')
            ->notSeeInDatabase('links', [
                'code' => '1'
            ])
            ->seeJson(['url' => ['Please enter a URL to shorten.']])
            ->assertResponseStatus(422);
    }

    /** @test */
    public function fails_if_url_is_invalid()
    {
        $response = $this->json('POST', '/', [
            'url' => 'http://google^&$$^&*^',
        ])
            ->notSeeInDatabase('links', [
                'code' => '1'
            ])
            ->seeJson(['url' => ['Hmm, that doesn\'t look like a valid URL.']])
            ->assertResponseStatus(422);
    }

    /** @test */
    public function link_can_be_shortened()
    {
        $this->json('POST', '/', [
            'url' => 'www.google.com'
        ])
        ->seeInDatabase('links', [
            'original_url' => 'http://www.google.com',
            'code' => '1'
        ])
        ->seeJson([
            'data' => [
                'original_url' => 'http://www.google.com',
                'shortened_url' => env('CLIENT_URL') . '/1',
                'code' => '1'
            ]
        ])
        ->assertResponseStatus(200);
    }

    /** @test */
    public function link_is_only_shortened_once()
    {
        $url = 'http://www.google.com';

        $this->json('POST', '/', ['url' => $url]);
        $this->json('POST', '/', ['url' => $url]);

        $link = Link::where('original_url', $url)->get();

        $this->assertCount(1, $link);
    }

    /** @test */
    public function requested_count_is_incremented()
    {
        $url = 'http://www.google.com';

        $this->json('POST', '/', ['url' => $url]);
        $this->json('POST', '/', ['url' => $url]);

        $this->seeInDatabase('links', [
            'original_url' => $url,
            'requested_count' => 2
        ]);
    }

    /** @test */
    public function last_requested_date_is_updated_for_existing_link()
    {
        Link::flushEventListeners();

        $link = factory(Link::class)->create([
            'last_requested' => Carbon::now()->subDays(2)
        ]);

        $this->json('POST', '/', ['url' => $link->original_url])
            ->seeInDatabase('links', [
                'original_url' => $link->original_url,
                'last_requested' => Carbon::now()->toDateTimeString()
            ]);
    }
}
