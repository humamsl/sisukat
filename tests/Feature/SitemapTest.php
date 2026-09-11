<?php

use App\Models\Book;

test('sitemap renders valid xml including published content', function () {
    Book::create(['title' => 'Buku Sitemap', 'slug' => 'buku-sitemap', 'status' => 'published']);

    $response = $this->get(route('sitemap'));

    $response->assertOk();
    expect($response->headers->get('Content-Type'))->toContain('application/xml');
    $response->assertSee('buku-sitemap', false);
    $response->assertSee('<urlset', false);
});
