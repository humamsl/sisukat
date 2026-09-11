<?php

test('unknown routes render the custom 404 page', function () {
    $response = $this->get('/halaman-tidak-ada-xyz');

    $response->assertNotFound()->assertSee('Halaman Tidak Ditemukan');
});

test('responses include secure headers', function () {
    $response = $this->get('/');

    $response->assertHeader('X-Content-Type-Options', 'nosniff');
    $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
});
