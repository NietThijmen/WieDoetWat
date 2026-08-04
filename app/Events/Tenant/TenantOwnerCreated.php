<?php

namespace App\Events\Tenant;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class TenantOwnerCreated
{
    use Dispatchable;

    public function __construct(
        Tenant $tenant,
        User $user
    ) {
        //
    }
}
