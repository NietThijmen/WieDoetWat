<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

uses(RefreshDatabase::class);

test('a visitor can register a new tenant', function () {
    Tenant::withoutEvents(function () {
        $response = $this->post('http://wie-doet-wat.test/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'subdomain' => 'john-corp',
        ]);

        $response->assertRedirect('http://john-corp.wie-doet-wat.test');

        $this->assertDatabaseHas('tenants', ['id' => 'john-corp']);
        $this->assertDatabaseHas('domains', [
            'domain' => 'john-corp',
            'tenant_id' => 'john-corp',
        ]);

        $user = User::where('email', 'john@example.com')->first();

        expect($user)->not->toBeNull();
        expect($user->tenant_id)->toBe('john-corp');
        expect(Hash::check('password', $user->password))->toBeTrue();

        $this->assertAuthenticatedAs($user);
    });
});

test('registration validation rejects invalid input', function () {
    Tenant::withoutEvents(function () {
        Tenant::create(['id' => 'existing-tenant'])
            ->domains()
            ->create(['domain' => 'existing-tenant']);

        $response = $this->post('http://wie-doet-wat.test/register', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'short',
            'password_confirmation' => 'mismatch',
            'subdomain' => 'existing-tenant',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'subdomain']);
    });
});

test('registration persists authentication on the tenant subdomain', function () {
    config(['session.domain' => '.wie-doet-wat.test']);

    Route::middleware(['web', 'tenant'])->get('/_test/auth', function () {
        return [
            'authenticated' => Auth::check(),
            'user' => Auth::user()?->email,
        ];
    });

    Tenant::withoutEvents(function () {
        $response = $this->post('http://wie-doet-wat.test/register', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'subdomain' => 'jane-corp',
        ]);

        $response->assertRedirect('http://jane-corp.wie-doet-wat.test');
    });

    $subdomainResponse = $this->get('http://jane-corp.wie-doet-wat.test/_test/auth');

    $subdomainResponse->assertOk()->assertJson([
        'authenticated' => true,
        'user' => 'jane@example.com',
    ]);
});

test('reserved subdomains cannot be registered', function () {
    $response = $this->post('http://wie-doet-wat.test/register', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'subdomain' => 'www',
    ]);

    $response->assertSessionHasErrors(['subdomain']);
});
