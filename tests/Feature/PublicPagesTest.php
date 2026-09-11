<?php

use App\Models\Page;

test('home page loads successfully', function () {
    $this->get(route('home'))->assertOk()->assertSee('SISUKAT');
});

test('pendahuluan page renders published page content', function () {
    $page = Page::create(['slug' => 'pendahuluan', 'title' => 'Pendahuluan', 'content' => '<p>Isi</p>', 'status' => 'published']);

    $response = $this->get(route('pages.pendahuluan'));

    $response->assertOk()->assertSee($page->title);
});

test('petunjuk penggunaan page renders published page content', function () {
    $page = Page::create(['slug' => 'petunjuk-penggunaan', 'title' => 'Petunjuk Penggunaan', 'content' => '<ol><li>Langkah</li></ol>', 'status' => 'published']);

    $response = $this->get(route('pages.petunjuk'));

    $response->assertOk()->assertSee($page->title);
});

test('unpublished page returns 404', function () {
    Page::create(['slug' => 'pendahuluan', 'title' => 'Draft', 'content' => 'x', 'status' => 'draft']);

    $this->get(route('pages.pendahuluan'))->assertNotFound();
});
