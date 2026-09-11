<?php

use App\Models\Tutorial;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
    $this->admin = User::factory()->create();
});

test('admin can create an article tutorial', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.tutorials.store'), [
        'title' => 'Tutorial Artikel',
        'type' => 'article',
        'content' => '<p>Isi artikel</p><script>alert(1)</script>',
        'status' => 'published',
    ]);

    $response->assertRedirect(route('admin.tutorials.index'));

    $tutorial = Tutorial::firstWhere('title', 'Tutorial Artikel');
    expect($tutorial)->not->toBeNull();
    expect($tutorial->content)->toContain('<p>Isi artikel</p>');
    expect($tutorial->content)->not->toContain('<script>');
});

test('video tutorial requires a video url', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.tutorials.store'), [
        'title' => 'Tutorial Video',
        'type' => 'video',
        'status' => 'published',
    ]);

    $response->assertSessionHasErrors('video_url');
});

test('pdf tutorial requires a pdf file', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.tutorials.store'), [
        'title' => 'Tutorial PDF',
        'type' => 'pdf',
        'status' => 'published',
    ]);

    $response->assertSessionHasErrors('file');
});

test('admin can create a video tutorial and embed url resolves', function () {
    $this->actingAs($this->admin)->post(route('admin.tutorials.store'), [
        'title' => 'Tutorial Video Valid',
        'type' => 'video',
        'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        'status' => 'published',
    ]);

    $tutorial = Tutorial::firstWhere('title', 'Tutorial Video Valid');
    expect($tutorial->embed_url)->toBe('https://www.youtube.com/embed/dQw4w9WgXcQ');
});

test('admin can delete a tutorial', function () {
    $tutorial = Tutorial::create(['title' => 'Hapus', 'slug' => 'hapus', 'type' => 'article', 'content' => 'x', 'status' => 'published']);

    $this->actingAs($this->admin)->delete(route('admin.tutorials.destroy', $tutorial))
        ->assertRedirect(route('admin.tutorials.index'));

    expect(Tutorial::find($tutorial->id))->toBeNull();
});
