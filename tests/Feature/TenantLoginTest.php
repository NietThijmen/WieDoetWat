<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('a tenant login page is shown on a tenant subdomain', function () {
    Tenant::withoutEvents(function () {
        Tenant::create(['id' => 'john-corp'])
            ->domains()
            ->create(['domain' => 'john-corp']);
    });

    $response = $this->get('http://john-corp.wie-doet-wat.test/login');

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page->component('Login'));
});

test('a tenant user can log in on their subdomain', function () {
    Tenant::withoutEvents(function () {
        Tenant::create(['id' => 'jane-corp'])
            ->domains()
            ->create(['domain' => 'jane-corp']);

        User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'tenant_id' => 'jane-corp',
        ]);
    });

    $response = $this->post('http://jane-corp.wie-doet-wat.test/login', [
        'email' => 'jane@example.com',
        'password' => 'password',
    ]);

    $response->assertRedirect('/home');
    $this->assertAuthenticated();
});

test('tenant login rejects invalid credentials', function () {
    Tenant::withoutEvents(function () {
        Tenant::create(['id' => 'invalid-corp'])
            ->domains()
            ->create(['domain' => 'invalid-corp']);

        User::create([
            'name' => 'Invalid User',
            'email' => 'invalid@example.com',
            'password' => Hash::make('password'),
            'tenant_id' => 'invalid-corp',
        ]);
    });

    $response = $this->post('http://invalid-corp.wie-doet-wat.test/login', [
        'email' => 'invalid@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertRedirect();
    $this->assertGuest();
});
