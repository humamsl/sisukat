<?php

use App\Models\Book;
use App\Models\Instrument;
use App\Models\Tutorial;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('guest is redirected to login', function () {
    $this->get(route('search.index'))->assertRedirect(route('login'));
});

test('search without a query shows a prompt instead of results', function () {
    $this->actingAs($this->user)->get(route('search.index'))->assertOk()->assertSee('Masukkan kata kunci');
});

test('search finds published items across all three modules, case-insensitively', function () {
    Book::create(['title' => 'Panduan Supervisi Akademik', 'slug' => 'panduan-supervisi', 'status' => 'published']);
    Tutorial::create(['title' => 'Video Supervisi Kelas', 'slug' => 'video-supervisi', 'type' => 'video', 'video_url' => 'https://youtube.com/watch?v=abcdefghijk', 'status' => 'published']);
    Instrument::create(['title' => 'Instrumen Supervisi Guru', 'slug' => 'instrumen-supervisi', 'file' => 'i.pdf', 'file_type' => 'pdf', 'file_size' => 10, 'status' => 'published']);
    Book::create(['title' => 'Buku Tidak Terkait', 'slug' => 'buku-tidak-terkait', 'status' => 'published']);

    $response = $this->actingAs($this->user)->get(route('search.index', ['q' => 'SUPERVISI']));

    $response->assertOk()
        ->assertSee('Panduan Supervisi Akademik')
        ->assertSee('Video Supervisi Kelas')
        ->assertSee('Instrumen Supervisi Guru')
        ->assertDontSee('Buku Tidak Terkait');
});

test('search excludes draft items', function () {
    Book::create(['title' => 'Buku Draft Rahasia', 'slug' => 'buku-draft-rahasia', 'status' => 'draft']);

    $response = $this->actingAs($this->user)->get(route('search.index', ['q' => 'Rahasia']));

    $response->assertOk()->assertDontSee('Buku Draft Rahasia');
});

test('search shows an empty state when nothing matches', function () {
    $response = $this->actingAs($this->user)->get(route('search.index', ['q' => 'kata-yang-tidak-ada-sama-sekali']));

    $response->assertOk()->assertSee('Tidak ada hasil');
});
