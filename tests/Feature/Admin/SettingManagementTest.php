<?php

use App\Models\Setting;
use App\Models\User;

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
