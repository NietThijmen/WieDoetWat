<?php

namespace App\Actions\TenantRegistration;

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

            return $tenant;
        });
    }
}
