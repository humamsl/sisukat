<?php

use App\Models\User;

test('super admin can view activity logs', function () {
    $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

    $this->actingAs($superAdmin)->get(route('admin.activity-logs.index'))->assertOk();
});

test('regular admin cannot view activity logs', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $this->actingAs($admin)->get(route('admin.activity-logs.index'))->assertForbidden();
});
