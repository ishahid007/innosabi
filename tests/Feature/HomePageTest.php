<?php

declare(strict_types=1);

it('homepage loads successfully', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});

it('returns 404 error for non-existent route', function () {
    // Simulate a request to a non-existent route
    $response = $this->getJson('/non-existent-route');

    // Assert the response status is 404 Not Found
    $response->assertStatus(404);

    // Assert the response JSON matches the expected structure
    $response->assertJson([
        'message' => 'Object not found',
    ]);
});

it('returns 405 error for unsupported HTTP method', function () {
    // Simulate a POST request to a GET-only route
    $response = $this->postJson('/'); // Assuming the homepage only allows GET requests

    // Assert the response status is 405 Method Not Allowed
    $response->assertStatus(405);

    // Assert the response JSON matches the expected structure
    $response->assertJson([
        'message' => 'Method not allowed',
    ]);
});

it('handles 404 error for non-existent API route', function () {
    // Simulate a request to a non-existent API route
    $response = $this->getJson('/api/non-existent-route');

    // Assert the response status is 404 Not Found
    $response->assertStatus(404);

    // Assert the response JSON matches the expected structure
    $response->assertJson([
        'message' => 'Object not found',
    ]);
});

it('handles 405 error for unsupported HTTP method on API route', function () {
    // Simulate a DELETE request to a GET-only API route
    $response = $this->deleteJson('/api/suggestion'); // Assuming this route only allows GET

    // Assert the response status is 405 Method Not Allowed
    $response->assertStatus(405);

    // Assert the response JSON matches the expected structure
    $response->assertJson([
        'message' => 'Method not allowed',
    ]);
});
