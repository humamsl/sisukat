<?php

use App\Models\Instrument;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
    $this->admin = User::factory()->create();
});

test('admin can create an instrument and file metadata is derived automatically', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.instruments.store'), [
        'title' => 'Instrumen Observasi',
        'year' => 2026,
        'file' => UploadedFile::fake()->create('instrumen.docx', 200, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
        'status' => 'published',
    ]);

    $response->assertRedirect(route('admin.instruments.index'));

    $instrument = Instrument::firstWhere('title', 'Instrumen Observasi');
    expect($instrument->file_type)->toBe('docx');
    expect($instrument->file_size)->toBeGreaterThan(0);
    Storage::disk('local')->assertExists($instrument->file);
});

test('instrument creation rejects disallowed file types', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.instruments.store'), [
        'title' => 'Instrumen Invalid',
        'file' => UploadedFile::fake()->create('script.exe', 10, 'application/x-msdownload'),
        'status' => 'published',
    ]);

    $response->assertSessionHasErrors('file');
});

test('admin can delete an instrument and its file is removed', function () {
    Storage::disk('local')->put('instruments/x.pdf', 'dummy');
    $instrument = Instrument::create([
        'title' => 'Hapus', 'slug' => 'hapus-instrumen', 'file' => 'instruments/x.pdf',
        'file_type' => 'pdf', 'file_size' => 5, 'status' => 'published',
    ]);

    $this->actingAs($this->admin)->delete(route('admin.instruments.destroy', $instrument))
        ->assertRedirect(route('admin.instruments.index'));

    expect(Instrument::find($instrument->id))->toBeNull();
    Storage::disk('local')->assertMissing('instruments/x.pdf');
});
