<?php

use App\Models\Upload;
use App\Models\User;
use App\Notifications\NewUploadSubmitted;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
    Notification::fake();
});

function validUploadPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'identity_number' => '198001012020121001',
        'position' => 'Guru',
        'school' => 'SDN Contoh 1',
        'document_type' => 'Laporan Hasil Supervisi',
        'description' => 'Laporan bulan ini',
        'file' => UploadedFile::fake()->create('laporan.pdf', 300, 'application/pdf'),
        'agreement' => '1',
    ], $overrides);
}

test('a visitor can submit a document upload', function () {
    $response = $this->post(route('upload.store'), validUploadPayload());

    $response->assertRedirect(route('upload.create'));
    $response->assertSessionHas('status');

    $upload = Upload::firstWhere('email', 'budi@example.com');
    expect($upload)->not->toBeNull();
    expect($upload->original_filename)->toBe('laporan.pdf');
    expect($upload->file)->not->toBe('laporan.pdf');
    expect($upload->status)->toBe('pending');
    Storage::disk('local')->assertExists($upload->file);
});

test('upload requires the agreement checkbox to be accepted', function () {
    $response = $this->post(route('upload.store'), validUploadPayload(['agreement' => null]));

    $response->assertSessionHasErrors('agreement');
});

test('upload rejects disallowed file types such as php', function () {
    $response = $this->post(route('upload.store'), validUploadPayload([
        'file' => UploadedFile::fake()->create('shell.php', 10, 'application/x-httpd-php'),
    ]));

    $response->assertSessionHasErrors('file');
    expect(Upload::count())->toBe(0);
});

test('upload rejects files over the configured size limit', function () {
    $maxKb = config('sisukat.uploads.document_max_kb');

    $response = $this->post(route('upload.store'), validUploadPayload([
        'file' => UploadedFile::fake()->create('besar.pdf', $maxKb + 500, 'application/pdf'),
    ]));

    $response->assertSessionHasErrors('file');
});

test('upload notifies active admins but not inactive ones', function () {
    $admin = User::factory()->create();
    $inactive = User::factory()->create(['is_active' => false]);

    $this->post(route('upload.store'), validUploadPayload());

    Notification::assertSentTo($admin, NewUploadSubmitted::class);
    Notification::assertNotSentTo($inactive, NewUploadSubmitted::class);
});
