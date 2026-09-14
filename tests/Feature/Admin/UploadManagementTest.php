<?php

use App\Models\Upload;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
    $this->admin = User::factory()->create();
});

function makeUpload(array $overrides = []): Upload
{
    Storage::disk('local')->put('uploads/doc.pdf', 'dummy content');

    return Upload::create(array_merge([
        'user_id' => User::factory()->create(['school' => 'SDN 1'])->id,
        'file' => 'uploads/doc.pdf', 'original_filename' => 'doc.pdf',
        'file_size' => 13, 'mime_type' => 'application/pdf', 'status' => 'pending',
        'ip_address' => '127.0.0.1', 'uploaded_at' => now(),
    ], $overrides));
}

test('guest cannot view the upload inbox', function () {
    $this->get(route('admin.uploads.index'))->assertRedirect(route('login'));
});

test('admin can view and download an uploaded document', function () {
    $upload = makeUpload();

    $this->actingAs($this->admin)->get(route('admin.uploads.show', $upload))->assertOk()->assertSee('doc.pdf');
    $this->actingAs($this->admin)->get(route('admin.uploads.download', $upload))->assertOk();
});

test('admin can change an upload status', function () {
    $upload = makeUpload();

    $response = $this->actingAs($this->admin)->patch(route('admin.uploads.status', $upload), ['status' => 'reviewed']);

    $response->assertRedirect();
    expect($upload->fresh()->status)->toBe('reviewed');
});

test('admin can delete an upload and its file', function () {
    $upload = makeUpload();

    $this->actingAs($this->admin)->delete(route('admin.uploads.destroy', $upload))
        ->assertRedirect(route('admin.uploads.index'));

    expect(Upload::find($upload->id))->toBeNull();
    Storage::disk('local')->assertMissing('uploads/doc.pdf');
});
