<?php

namespace App\Actions\TenantRegistration;

use App\Events\Tenant\TenantCreated;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class CreateTenant
{
    public function handle(string $subdomain): Tenant
    {
        return DB::transaction(function () use ($subdomain): Tenant {
            $tenant = Tenant::create([
                'id' => $subdomain,
            ]);

            $tenant->domains()->create([
                'domain' => $subdomain,
            ]);

            TenantCreated::dispatch(
                $tenant,
                $subdomain
            );

            return $tenant;
        });
    }
}
