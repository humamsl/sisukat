<?php

use App\Models\User;

test('guest is redirected to login when visiting the admin dashboard', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
});

test('a regular user account cannot access the admin dashboard', function () {
    $user = User::factory()->create(['role' => User::ROLE_USER]);

    $response = $this->actingAs($user)->get(route('admin.dashboard'));

    $response->assertRedirect(route('home'));
});

test('staff roles can access the admin dashboard', function () {
    foreach ([User::ROLE_ADMIN, User::ROLE_SUPER_ADMIN, User::ROLE_REVIEWER] as $role) {
        $staff = User::factory()->create(['role' => $role]);

        $this->actingAs($staff)->get(route('admin.dashboard'))->assertOk();
    }
});

test('an inactive staff account is blocked from the admin dashboard', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'is_active' => false]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertRedirect(route('home'));
});
