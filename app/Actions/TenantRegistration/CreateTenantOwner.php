<?php

namespace App\Actions\TenantRegistration;

use App\Events\Tenant\TenantOwnerCreated;
use App\Models\Tenant;
use App\Models\User;

class CreateTenantOwner
{
    public function handle(Tenant $tenant, string $name, string $email, string $password): User
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'tenant_id' => $tenant->id,
            'is_admin' => true,
        ]);

        TenantOwnerCreated::dispatch(
            $tenant,
            $user
        );

        return $user;
    }
}
