<?php

test('homepage loads successfully', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});

// test('homepage contains expected content', function () {
//     $reponse = $this->get('/');
//     $response->assertSee('Search suggestions');
//     $response->assertStatus(200);
// });
