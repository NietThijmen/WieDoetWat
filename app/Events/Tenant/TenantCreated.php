<?php

namespace App\Events\Tenant;

use App\Models\Tenant;
use Illuminate\Foundation\Events\Dispatchable;

class TenantCreated
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Tenant $tenant,
        public string $subdomain
    ) {
        //
    }
}
