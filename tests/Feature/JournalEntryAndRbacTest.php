<?php

use App\Models\User;

test('unauthenticated user is redirected to login', function () {
    $response = $this->get('/');

    // Pastikan diarahkan ke halaman login
    $response->assertRedirect('/login');
});
