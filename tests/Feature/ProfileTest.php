<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('guest cannot access the profile page', function () {
    $this->get(route('profile.edit'))->assertRedirect(route('login'));
});

test('a logged in user can view their profile', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('profile.edit'))->assertOk()->assertSee($user->email);
});

test('a user can change their own password', function () {
    $user = User::factory()->create(['password' => bcrypt('old-password')]);

    $response = $this->actingAs($user)->put(route('profile.password'), [
        'current_password' => 'old-password',
        'password' => 'new-password-123',
        'password_confirmation' => 'new-password-123',
    ]);

    $response->assertRedirect();
    $this->assertTrue(Hash::check('new-password-123', $user->fresh()->password));
});

test('changing password fails when the current password is wrong', function () {
    $user = User::factory()->create(['password' => bcrypt('old-password')]);

    $response = $this->actingAs($user)->put(route('profile.password'), [
        'current_password' => 'wrong-password',
        'password' => 'new-password-123',
        'password_confirmation' => 'new-password-123',
    ]);

    $response->assertSessionHasErrors('current_password');
    $this->assertTrue(Hash::check('old-password', $user->fresh()->password));
});
