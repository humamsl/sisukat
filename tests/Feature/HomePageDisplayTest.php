<?php

use App\Models\Setting;

test('running text marquee is hidden when no running text is configured', function () {
    $this->get(route('home'))->assertOk()->assertDontSee('animate-marquee', false);
});

test('running text marquee renders when configured', function () {
    Setting::create(['key' => 'running_text', 'value' => 'Info penting berjalan di sini']);

    $response = $this->get(route('home'));

    $response->assertOk()->assertSee('Info penting berjalan di sini');
});

test('hero section falls back to solid background when no hero image is configured', function () {
    $response = $this->get(route('home'));

    $response->assertOk()->assertDontSee('background-image', false);
});
