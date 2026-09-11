<?php

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('super admin can update site settings', function () {
    $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

    $response = $this->actingAs($superAdmin)->put(route('admin.settings.update'), [
        'site_name' => 'SISUKAT Terbaru',
        'copyright_text' => 'SISUKAT. Hak cipta dilindungi.',
    ]);

    $response->assertRedirect();
    expect(Setting::where('key', 'site_name')->value('value'))->toBe('SISUKAT Terbaru');
});

test('regular admin cannot update settings', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $this->actingAs($admin)->get(route('admin.settings.edit'))->assertForbidden();
});

test('super admin can upload a logo image', function () {
    Storage::fake('public');
    $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

    $response = $this->actingAs($superAdmin)->put(route('admin.settings.update'), [
        'site_name' => 'SISUKAT',
        'logo' => UploadedFile::fake()->image('logo.png', 300, 300)->size(500),
    ]);

    $response->assertSessionDoesntHaveErrors();
    $path = Setting::where('key', 'logo')->value('value');
    expect($path)->not->toBeNull();
    Storage::disk('public')->assertExists($path);
});

test('super admin can upload an ico favicon', function () {
    Storage::fake('public');
    $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

    $response = $this->actingAs($superAdmin)->put(route('admin.settings.update'), [
        'site_name' => 'SISUKAT',
        'favicon' => UploadedFile::fake()->create('favicon.ico', 50, 'image/x-icon'),
    ]);

    $response->assertSessionDoesntHaveErrors();
    $path = Setting::where('key', 'favicon')->value('value');
    expect($path)->not->toBeNull();
    Storage::disk('public')->assertExists($path);
});

test('super admin can upload a hero background image', function () {
    Storage::fake('public');
    $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

    $response = $this->actingAs($superAdmin)->put(route('admin.settings.update'), [
        'site_name' => 'SISUKAT',
        'hero_background' => UploadedFile::fake()->image('hero.jpg', 1600, 900)->size(1000),
    ]);

    $response->assertSessionDoesntHaveErrors();
    $path = Setting::where('key', 'hero_background')->value('value');
    expect($path)->not->toBeNull();
    Storage::disk('public')->assertExists($path);
});

test('an oversized logo is rejected with a visible validation error', function () {
    Storage::fake('public');
    $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
    $maxKb = config('sisukat.uploads.logo_max_kb');

    $response = $this->actingAs($superAdmin)->put(route('admin.settings.update'), [
        'site_name' => 'SISUKAT',
        'logo' => UploadedFile::fake()->image('logo.png')->size($maxKb + 500),
    ]);

    $response->assertSessionHasErrors('logo');
    expect(Setting::where('key', 'logo')->value('value'))->toBeNull();
});

test('super admin can set the running text', function () {
    $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

    $this->actingAs($superAdmin)->put(route('admin.settings.update'), [
        'site_name' => 'SISUKAT',
        'running_text' => 'Info penting berjalan di sini',
    ])->assertSessionDoesntHaveErrors();

    expect(Setting::where('key', 'running_text')->value('value'))->toBe('Info penting berjalan di sini');
});
