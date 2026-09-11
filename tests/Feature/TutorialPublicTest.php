<?php

use App\Models\Tutorial;

test('published tutorials are listed and draft ones are hidden', function () {
    Tutorial::create(['title' => 'Tutorial Publik', 'slug' => 'tutorial-publik', 'type' => 'article', 'content' => 'x', 'status' => 'published']);
    Tutorial::create(['title' => 'Tutorial Draft', 'slug' => 'tutorial-draft', 'type' => 'article', 'content' => 'x', 'status' => 'draft']);

    $response = $this->get(route('tutorials.index'));

    $response->assertOk()->assertSee('Tutorial Publik')->assertDontSee('Tutorial Draft');
});

test('draft tutorial detail returns 404', function () {
    $tutorial = Tutorial::create(['title' => 'Draft', 'slug' => 'draft-x', 'type' => 'article', 'content' => 'x', 'status' => 'draft']);

    $this->get(route('tutorials.show', $tutorial))->assertNotFound();
});

test('article tutorial renders its content on the show page', function () {
    $tutorial = Tutorial::create([
        'title' => 'Artikel Tampil', 'slug' => 'artikel-tampil', 'type' => 'article',
        'content' => '<p>Konten khusus artikel</p>', 'status' => 'published',
    ]);

    $this->get(route('tutorials.show', $tutorial))->assertOk()->assertSee('Konten khusus artikel', false);
});
