<?php

use App\Models\Page;
use App\Models\User;

test('home page loads successfully for guests', function () {
    $this->get(route('home'))->assertOk()->assertSee('SISUKAT');
});

test('pendahuluan page renders published page content', function () {
    $user = User::factory()->create();
    $page = Page::create(['slug' => 'pendahuluan', 'title' => 'Pendahuluan', 'content' => '<p>Isi</p>', 'status' => 'published']);

    $response = $this->actingAs($user)->get(route('pages.pendahuluan'));

    $response->assertOk()->assertSee($page->title);
});

test('petunjuk penggunaan page renders published page content', function () {
    $user = User::factory()->create();
    $page = Page::create(['slug' => 'petunjuk-penggunaan', 'title' => 'Petunjuk Penggunaan', 'content' => '<ol><li>Langkah</li></ol>', 'status' => 'published']);

    $response = $this->actingAs($user)->get(route('pages.petunjuk'));

    $response->assertOk()->assertSee($page->title);
});

test('unpublished page returns 404', function () {
    $user = User::factory()->create();
    Page::create(['slug' => 'pendahuluan', 'title' => 'Draft', 'content' => 'x', 'status' => 'draft']);

    $this->actingAs($user)->get(route('pages.pendahuluan'))->assertNotFound();
});

test('guest is redirected to login for gated content pages', function () {
    $this->get(route('pages.pendahuluan'))->assertRedirect(route('login'));
});
