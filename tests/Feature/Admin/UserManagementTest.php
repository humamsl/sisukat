<?php

use App\Models\User;

test('super admin can create a new admin user', function () {
    $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

    $response = $this->actingAs($superAdmin)->post(route('admin.users.store'), [
        'name' => 'Admin Baru',
        'email' => 'baru@sisukat.local',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'admin',
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.users.index'));
    expect(User::where('email', 'baru@sisukat.local')->exists())->toBeTrue();
});

test('regular admin cannot access user management', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $this->actingAs($admin)->get(route('admin.users.index'))->assertForbidden();
});

test('super admin cannot delete their own account', function () {
    $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

    $this->actingAs($superAdmin)->delete(route('admin.users.destroy', $superAdmin))->assertForbidden();

    expect(User::find($superAdmin->id))->not->toBeNull();
});

test('super admin can update another admin without changing the password', function () {
    $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
    $target = User::factory()->create(['name' => 'Lama']);
    $originalPassword = $target->password;

    $this->actingAs($superAdmin)->put(route('admin.users.update', $target), [
        'name' => 'Nama Baru',
        'email' => $target->email,
        'role' => 'admin',
        'is_active' => '1',
    ])->assertRedirect(route('admin.users.index'));

    $target->refresh();
    expect($target->name)->toBe('Nama Baru');
    expect($target->password)->toBe($originalPassword);
});
