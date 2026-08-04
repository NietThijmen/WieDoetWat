<?php

namespace App\Actions\TenantRegistration;

use App\Models\Tenant;
use App\Models\User;

class CreateTenantOwner
{
    public function handle(Tenant $tenant, string $name, string $email, string $password): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'tenant_id' => $tenant->id,
        ]);
    }
}
