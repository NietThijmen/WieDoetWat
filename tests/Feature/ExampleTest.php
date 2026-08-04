<?php

use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the central domain homepage returns a successful response', function () {
    $response = $this->get('http://wie-doet-wat.test/');

    $response->assertStatus(200);
});

test('the tenant domain homepage returns a successful response', function () {
    Tenant::withoutEvents(function () {
        $tenant = Tenant::create([
            'id' => 'test-tenant',
        ]);
        $tenant->domains()->create(['domain' => 'test-tenant']);
    });

    $response = $this->get('http://test-tenant.wie-doet-wat.test/');

    $response->assertStatus(200)
        ->assertSee('test-tenant');
});
