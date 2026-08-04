<?php

declare(strict_types=1);

use App\Models\Task;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createTenant(string $subdomain): Tenant
{
    return Tenant::withoutEvents(function () use ($subdomain): Tenant {
        $tenant = Tenant::create(['id' => $subdomain]);
        $tenant->domains()->create(['domain' => $subdomain]);

        return $tenant;
    });
}

function tenantUrl(string $subdomain, string $path): string
{
    return "http://{$subdomain}.wie-doet-wat.test{$path}";
}

test('an admin can view the admin dashboard', function () {
    $tenant = createTenant('admin-corp');

    $admin = User::factory()->admin()->create([
        'tenant_id' => $tenant->id,
    ]);

    $response = $this->actingAs($admin)->get(tenantUrl('admin-corp', '/admin'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page->component('Admin/Index'));
});

test('a non-admin cannot view the admin dashboard', function () {
    $tenant = createTenant('user-corp');

    $user = User::factory()->create([
        'tenant_id' => $tenant->id,
    ]);

    $response = $this->actingAs($user)->get(tenantUrl('user-corp', '/admin'));

    $response->assertForbidden();
});

test('a guest is redirected from the admin dashboard', function () {
    createTenant('guest-corp');

    $response = $this->get(tenantUrl('guest-corp', '/admin'));

    $response->assertRedirect('/login');
});

test('an admin can create a new user', function () {
    $tenant = createTenant('create-user-corp');

    $admin = User::factory()->admin()->create([
        'tenant_id' => $tenant->id,
    ]);

    $response = $this->actingAs($admin)->post(tenantUrl('create-user-corp', '/admin/users'), [
        'name' => 'New User',
        'email' => 'new@example.com',
        'password' => 'password123!',
        'password_confirmation' => 'password123!',
        'is_admin' => false,
    ]);

    $response->assertRedirect('/admin');

    $this->assertDatabaseHas((new User)->getTable(), [
        'name' => 'New User',
        'email' => 'new@example.com',
        'tenant_id' => $tenant->id,
        'is_admin' => false,
    ]);
});

test('a non-admin cannot create a user', function () {
    $tenant = createTenant('no-create-user-corp');

    $user = User::factory()->create([
        'tenant_id' => $tenant->id,
    ]);

    $response = $this->actingAs($user)->post(tenantUrl('no-create-user-corp', '/admin/users'), [
        'name' => 'Hacker',
        'email' => 'hacker@example.com',
        'password' => 'password123!',
        'password_confirmation' => 'password123!',
    ]);

    $response->assertForbidden();
});

test('an admin cannot create a user with a duplicate email', function () {
    $tenant = createTenant('duplicate-email-corp');

    $admin = User::factory()->admin()->create([
        'tenant_id' => $tenant->id,
    ]);

    User::factory()->create([
        'email' => 'existing@example.com',
        'tenant_id' => $tenant->id,
    ]);

    $response = $this->actingAs($admin)->post(tenantUrl('duplicate-email-corp', '/admin/users'), [
        'name' => 'New User',
        'email' => 'existing@example.com',
        'password' => 'password123!',
        'password_confirmation' => 'password123!',
    ]);

    $response->assertSessionHasErrors('email');
});

test('an admin can delete another user', function () {
    $tenant = createTenant('delete-user-corp');

    $admin = User::factory()->admin()->create([
        'tenant_id' => $tenant->id,
    ]);

    $target = User::factory()->create([
        'tenant_id' => $tenant->id,
    ]);

    $response = $this->actingAs($admin)->delete(tenantUrl('delete-user-corp', "/admin/users/{$target->id}"));

    $response->assertRedirect('/admin');
    $this->assertModelMissing($target);
});

test('an admin cannot delete themselves', function () {
    $tenant = createTenant('self-delete-corp');

    $admin = User::factory()->admin()->create([
        'tenant_id' => $tenant->id,
    ]);

    $response = $this->actingAs($admin)->delete(tenantUrl('self-delete-corp', "/admin/users/{$admin->id}"));

    $response->assertRedirect('/admin');
    $this->assertModelExists($admin);
});

test('an admin can create a task', function () {
    $tenant = createTenant('create-task-corp');

    $admin = User::factory()->admin()->create([
        'tenant_id' => $tenant->id,
    ]);

    $response = $this->actingAs($admin)->post(tenantUrl('create-task-corp', '/admin/tasks'), [
        'title' => 'Clean kitchen',
        'description' => 'Wipe counters',
        'weight' => 5,
    ]);

    $response->assertRedirect('/admin');

    $this->assertDatabaseHas((new Task)->getTable(), [
        'title' => 'Clean kitchen',
        'description' => 'Wipe counters',
        'weight' => 5,
        'tenant_id' => $tenant->id,
    ]);
});

test('a non-admin cannot create a task', function () {
    $tenant = createTenant('no-create-task-corp');

    $user = User::factory()->create([
        'tenant_id' => $tenant->id,
    ]);

    $response = $this->actingAs($user)->post(tenantUrl('no-create-task-corp', '/admin/tasks'), [
        'title' => 'Hacked task',
        'weight' => 1,
    ]);

    $response->assertForbidden();
});

test('an admin can delete a task', function () {
    $tenant = createTenant('delete-task-corp');

    $admin = User::factory()->admin()->create([
        'tenant_id' => $tenant->id,
    ]);

    $task = Task::factory()->create([
        'tenant_id' => $tenant->id,
    ]);

    $response = $this->actingAs($admin)->delete(tenantUrl('delete-task-corp', "/admin/tasks/{$task->id}"));

    $response->assertRedirect('/admin');
    $this->assertSoftDeleted($task);
});

test('a non-admin cannot delete a task', function () {
    $tenant = createTenant('no-delete-task-corp');

    $user = User::factory()->create([
        'tenant_id' => $tenant->id,
    ]);

    $task = Task::factory()->create([
        'tenant_id' => $tenant->id,
    ]);

    $response = $this->actingAs($user)->delete(tenantUrl('no-delete-task-corp', "/admin/tasks/{$task->id}"));

    $response->assertForbidden();
    $this->assertModelExists($task);
});

test('users from other tenants cannot be managed', function () {
    $tenantA = createTenant('tenant-a');
    $tenantB = createTenant('tenant-b');

    $adminA = User::factory()->admin()->create([
        'tenant_id' => $tenantA->id,
    ]);

    $userB = User::factory()->create([
        'tenant_id' => $tenantB->id,
    ]);

    $response = $this->actingAs($adminA)->delete(tenantUrl('tenant-a', "/admin/users/{$userB->id}"));

    $response->assertNotFound();
});
