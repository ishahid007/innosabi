<?php

declare(strict_types=1);

use App\Contracts\ApiService;
use GuzzleHttp\Psr7\Response as Psr7Response;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    // Clear the cache before each test
    Cache::flush();

    // Mock the ApiService in the container
    $this->mock(ApiService::class, function ($mock) {
        $mock->shouldReceive('get')->andReturn(
            new Response(
                new Psr7Response(
                    200,
                    ['Content-Type' => 'application/json'],
                    json_encode([
                        'data' => [
                            'id' => 1,
                            'title' => 'Test Suggestion',
                            'description' => 'This is a test suggestion from the third-party API.',
                        ],
                    ])
                )
            )
        );
    });
});

it('returns a successful response from the /api/suggestion endpoint', function () {
    // Call the /api/suggestion endpoint
    $response = $this->getJson('/api/suggestion?include=id,title');

    // Assert the response status is 200 OK
    $response->assertStatus(200);

    // Assert the response JSON matches the expected structure
    $response->assertJson([
        'data' => [
            'id' => 1,
            'title' => 'Test Suggestion',
            'description' => 'This is a test suggestion from the third-party API.',
        ],
    ]);

    // Assert the response is cached
    $cacheKey = 'suggestion_'.md5(json_encode(['include' => 'id,title']));
    expect(Cache::has($cacheKey))->toBeTrue();
});

it('returns 405 error for unsupported HTTP method', function () {
    // Simulate a POST request to a GET-only API route
    $response = $this->postJson('/api/suggestion', [
        'include' => 'id,title',
    ]);

    // Assert the response status is 405 Method Not Allowed
    $response->assertStatus(405);

    // Assert the response JSON matches the expected structure
    $response->assertJson([
        'message' => 'Method not allowed',
    ]);
});

it('rejects requests with extra parameters', function () {
    // Call the /api/suggestion endpoint with an extra parameter
    $response = $this->getJson('/api/suggestion?include=id,title&extra_param=value');

    // Assert the response status is 422 Unprocessable Entity
    $response->assertStatus(422);

    // Assert the response JSON contains the validation error message
    $response->assertJson([
        'message' => 'Unexpected parameters: extra_param',
        'errors' => [
            'error' => ['Unexpected parameters: extra_param'],
        ],
    ]);
});

it('returns a 500 error when the third-party API fails', function () {
    // Mock the ApiService to throw an exception
    $this->mock(ApiService::class, function ($mock) {
        $mock->shouldReceive('get')->andThrow(new \Exception('Third-party API failed'));
    });

    // Call the /api/suggestion endpoint
    $response = $this->getJson('/api/suggestion?include=id,title');

    // Assert the response status is 500
    $response->assertStatus(500);

    // Assert the response JSON contains the error message
    $response->assertJson([
        'message' => 'Third-party API failed',
    ]);
});

it('uses cache for identical requests to the /api/suggestion endpoint', function () {
    // Mock the ApiService to return data only once
    $this->mock(ApiService::class, function ($mock) {
        $mock->shouldReceive('get')
            ->once()
            ->andReturn(
                new Response(
                    new Psr7Response(
                        200,
                        ['Content-Type' => 'application/json'],
                        json_encode([
                            'data' => [
                                'id' => 1,
                                'title' => 'Test Suggestion',
                                'description' => 'This is a test suggestion from the third-party API.',
                            ],
                        ])
                    )
                )
            );
    });

    // Call the /api/suggestion endpoint twice
    $response1 = $this->getJson('/api/suggestion?include=id,title');
    $response2 = $this->getJson('/api/suggestion?include=id,title');

    // Assert both responses are the same
    expect($response1->json())->toBe($response2->json());

    // Assert the cache key exists
    $cacheKey = 'suggestion_'.md5(json_encode(['include' => 'id,title']));
    expect(Cache::has($cacheKey))->toBeTrue();
});

it('allows 60 requests per minute per IP', function () {
    // Simulate 60 requests from the same IP
    for ($i = 1; $i <= 60; $i++) {
        $response = $this->getJson('/api/suggestion?include=id,title');
        $response->assertStatus(200); // All requests should succeed
    }

    // The 61st request should be blocked
    $response = $this->getJson('/api/suggestion?include=id,title');
    $response->assertStatus(429); // Too Many Requests
    $response->assertJson([
        'message' => 'Too Many Attempts.',
    ]);
});

it('resets the rate limit after 1 minute', function () {
    // Simulate 60 requests from the same IP
    for ($i = 1; $i <= 60; $i++) {
        $this->getJson('/api/suggestion?include=id,title')->assertStatus(200);
    }

    // The 61st request should be blocked
    $this->getJson('/api/suggestion?include=id,title')->assertStatus(429);

    // Fast-forward time by 61 seconds to reset the rate limit
    $this->travel(61)->seconds();

    // The next request should succeed
    $this->getJson('/api/suggestion?include=id,title')->assertStatus(200);
});
