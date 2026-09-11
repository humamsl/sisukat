<?php

use App\Models\Setting;

test('Setting::get reads a stored value and falls back to default when missing', function () {
    Setting::create(['key' => 'site_name', 'value' => 'SISUKAT Uji']);

    expect(Setting::get('site_name'))->toBe('SISUKAT Uji');
    expect(Setting::get('tidak_ada', 'fallback'))->toBe('fallback');
});

test('Setting::get reflects updates without needing a fresh request', function () {
    Setting::create(['key' => 'site_name', 'value' => 'Lama']);
    expect(Setting::get('site_name'))->toBe('Lama');

    Setting::where('key', 'site_name')->first()->update(['value' => 'Baru']);

    expect(Setting::get('site_name'))->toBe('Baru');
});
