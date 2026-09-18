<?php

use App\Models\ActivityLog;
use App\Models\User;

test('super admin can view activity logs', function () {
    $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);

    $this->actingAs($superAdmin)->get(route('admin.activity-logs.index'))->assertOk();
});

test('regular admin cannot view activity logs', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $this->actingAs($admin)->get(route('admin.activity-logs.index'))->assertForbidden();
});

test('activity logs can be filtered to exclude admin/staff users', function () {
    $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
    $publicUser = User::factory()->create(['role' => User::ROLE_USER]);

    ActivityLog::create(['user_id' => $superAdmin->id, 'action' => 'login', 'description' => 'Admin login']);
    ActivityLog::create(['user_id' => $publicUser->id, 'action' => 'login', 'description' => 'User login']);

    $response = $this->actingAs($superAdmin)->get(route('admin.activity-logs.index', ['user_filter' => 'exclude_admin']));

    $response->assertOk();
    $response->assertSee('User login');
    $response->assertDontSee('Admin login');
});

test('activity logs can be filtered by a specific user', function () {
    $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
    $otherAdmin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    ActivityLog::create(['user_id' => $superAdmin->id, 'action' => 'login', 'description' => 'Log dari super admin']);
    ActivityLog::create(['user_id' => $otherAdmin->id, 'action' => 'login', 'description' => 'Log dari admin lain']);

    $response = $this->actingAs($superAdmin)->get(route('admin.activity-logs.index', ['user_filter' => $otherAdmin->id]));

    $response->assertOk();
    $response->assertSee('Log dari admin lain');
    $response->assertDontSee('Log dari super admin');
});

test('super admin can export activity logs to excel', function () {
    $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
    ActivityLog::create(['user_id' => $superAdmin->id, 'action' => 'login', 'description' => 'Login']);

    $response = $this->actingAs($superAdmin)->get(route('admin.activity-logs.export.excel'));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
});

test('super admin can export activity logs to word', function () {
    $superAdmin = User::factory()->create(['role' => User::ROLE_SUPER_ADMIN]);
    ActivityLog::create(['user_id' => $superAdmin->id, 'action' => 'login', 'description' => 'Login']);

    $response = $this->actingAs($superAdmin)->get(route('admin.activity-logs.export.word'));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
});

test('regular admin cannot export activity logs', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    $this->actingAs($admin)->get(route('admin.activity-logs.export.excel'))->assertForbidden();
    $this->actingAs($admin)->get(route('admin.activity-logs.export.word'))->assertForbidden();
});
