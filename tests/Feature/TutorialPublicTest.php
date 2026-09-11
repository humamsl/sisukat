<?php

use App\Models\Tutorial;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('guest is redirected to login', function () {
    $this->get(route('tutorials.index'))->assertRedirect(route('login'));
});

test('published tutorials are listed and draft ones are hidden', function () {
    Tutorial::create(['title' => 'Tutorial Publik', 'slug' => 'tutorial-publik', 'type' => 'article', 'content' => 'x', 'status' => 'published']);
    Tutorial::create(['title' => 'Tutorial Draft', 'slug' => 'tutorial-draft', 'type' => 'article', 'content' => 'x', 'status' => 'draft']);

    $response = $this->actingAs($this->user)->get(route('tutorials.index'));

    $response->assertOk()->assertSee('Tutorial Publik')->assertDontSee('Tutorial Draft');
});

test('draft tutorial detail returns 404', function () {
    $tutorial = Tutorial::create(['title' => 'Draft', 'slug' => 'draft-x', 'type' => 'article', 'content' => 'x', 'status' => 'draft']);

    $this->actingAs($this->user)->get(route('tutorials.show', $tutorial))->assertNotFound();
});

test('article tutorial renders its content on the show page', function () {
    $tutorial = Tutorial::create([
        'title' => 'Artikel Tampil', 'slug' => 'artikel-tampil', 'type' => 'article',
        'content' => '<p>Konten khusus artikel</p>', 'status' => 'published',
    ]);

    $this->actingAs($this->user)->get(route('tutorials.show', $tutorial))->assertOk()->assertSee('Konten khusus artikel', false);
});
