<?php

use App\Models\User;

test('admin login page is accessible to guests', function () {
    $this->get(route('admin.login'))->assertOk();
});

test('admin can login with correct credentials and reach the dashboard', function () {
    $admin = User::factory()->create([
        'password' => bcrypt('password'),
        'role' => User::ROLE_ADMIN,
        'is_active' => true,
    ]);

    $response = $this->post(route('admin.login.store'), [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('admin.dashboard'));
    $this->assertAuthenticatedAs($admin);
});

test('login fails with wrong password', function () {
    $admin = User::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $response = $this->post(route('admin.login.store'), [
        'email' => $admin->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('inactive admin is blocked from the dashboard even when authenticated', function () {
    $admin = User::factory()->create(['is_active' => false]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertRedirect(route('admin.login'));
    $this->assertGuest();
});

test('guest cannot access the dashboard', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
});

test('authenticated admin can logout', function () {
    $admin = User::factory()->create();

    $response = $this->actingAs($admin)->post(route('admin.logout'));

    $response->assertRedirect(route('admin.login'));
    $this->assertGuest();
});
