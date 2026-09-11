<?php

use App\Models\Page;
use App\Models\User;

test('admin can update page content and it is sanitized', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'is_active' => true]);
    $page = Page::create(['slug' => 'pendahuluan', 'title' => 'Pendahuluan', 'content' => '<p>lama</p>', 'status' => 'published']);

    $response = $this->actingAs($admin)->put(route('admin.pages.update', $page), [
        'title' => 'Pendahuluan Baru',
        'content' => '<h2>Judul</h2><p>Isi</p><script>alert(1)</script>',
    ]);

    $response->assertRedirect(route('admin.pages.edit', $page));

    $page->refresh();
    expect($page->title)->toBe('Pendahuluan Baru');
    expect($page->content)->toContain('<h2>Judul</h2>');
    expect($page->content)->not->toContain('<script>');
});

test('guest cannot edit pages', function () {
    $page = Page::create(['slug' => 'pendahuluan', 'title' => 'Pendahuluan', 'content' => '<p>x</p>', 'status' => 'published']);

    $this->get(route('admin.pages.edit', $page))->assertRedirect(route('admin.login'));
});
