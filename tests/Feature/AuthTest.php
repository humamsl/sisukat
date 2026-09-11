<?php

use App\Models\User;

test('login page is accessible to guests', function () {
    $this->get(route('login'))->assertOk();
});

test('register page is accessible to guests', function () {
    $this->get(route('register'))->assertOk();
});

test('a visitor can register and is logged in and redirected home', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'Pengguna Baru',
        'email' => 'baru@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('home'));
    $this->assertAuthenticated();

    $user = User::firstWhere('email', 'baru@example.com');
    expect($user->role)->toBe(User::ROLE_USER);
    expect($user->is_active)->toBeTrue();
});

test('registration requires matching password confirmation', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'Pengguna Baru',
        'email' => 'baru@example.com',
        'password' => 'password123',
        'password_confirmation' => 'beda',
    ]);

    $response->assertSessionHasErrors('password');
    expect(User::where('email', 'baru@example.com')->exists())->toBeFalse();
});

test('a user can login with correct credentials and is redirected home', function () {
    $user = User::factory()->create(['role' => User::ROLE_USER, 'password' => bcrypt('password')]);

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('home'));
    $this->assertAuthenticatedAs($user);
});

test('an admin can login with correct credentials and is also redirected home', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN, 'password' => bcrypt('password')]);

    $response = $this->post(route('login.store'), [
        'email' => $admin->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('home'));
    $this->assertAuthenticatedAs($admin);
});

test('login fails with wrong password', function () {
    $user = User::factory()->create(['password' => bcrypt('password')]);

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('inactive account is blocked from logging in', function () {
    $user = User::factory()->create(['password' => bcrypt('password'), 'is_active' => false]);

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('guest is redirected to login when visiting a gated page', function () {
    $this->get(route('pages.pendahuluan'))->assertRedirect(route('login'));
});

test('authenticated user can logout and is redirected home', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect(route('home'));
    $this->assertGuest();
});
