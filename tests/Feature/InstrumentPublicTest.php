<?php

use App\Models\Instrument;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
    $this->user = User::factory()->create();
});

test('guest is redirected to login', function () {
    $this->get(route('instruments.index'))->assertRedirect(route('login'));
});

test('published instruments are listed and draft ones are hidden', function () {
    Instrument::create(['title' => 'Instrumen Publik', 'slug' => 'instrumen-publik', 'file' => 'i.pdf', 'file_type' => 'pdf', 'file_size' => 10, 'status' => 'published']);
    Instrument::create(['title' => 'Instrumen Draft', 'slug' => 'instrumen-draft', 'file' => 'i2.pdf', 'file_type' => 'pdf', 'file_size' => 10, 'status' => 'draft']);

    $response = $this->actingAs($this->user)->get(route('instruments.index'));

    $response->assertOk()->assertSee('Instrumen Publik')->assertDontSee('Instrumen Draft');
});

test('downloading an instrument increments its counter', function () {
    Storage::disk('local')->put('instruments/sample.pdf', 'fake content');
    $instrument = Instrument::create([
        'title' => 'Instrumen Unduh', 'slug' => 'instrumen-unduh', 'file' => 'instruments/sample.pdf',
        'file_type' => 'pdf', 'file_size' => 12, 'status' => 'published', 'download_count' => 0,
    ]);

    $this->actingAs($this->user)->get(route('instruments.download', $instrument))->assertOk();

    expect($instrument->fresh()->download_count)->toBe(1);
});

test('draft instrument preview returns 404', function () {
    $instrument = Instrument::create([
        'title' => 'Draft', 'slug' => 'draft-instrumen', 'file' => 'i.pdf', 'file_type' => 'pdf', 'file_size' => 10, 'status' => 'draft',
    ]);

    $this->actingAs($this->user)->get(route('instruments.preview', $instrument))->assertNotFound();
});
