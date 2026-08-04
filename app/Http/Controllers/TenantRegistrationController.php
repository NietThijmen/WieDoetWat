<?php

namespace App\Http\Controllers;

use App\Actions\TenantRegistration\CreateTenant;
use App\Actions\TenantRegistration\CreateTenantOwner;
use App\Http\Requests\StoreTenantRegistrationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class TenantRegistrationController extends Controller
{
    public function __construct(
        private CreateTenant $createTenant,
        private CreateTenantOwner $createTenantOwner,
    ) {}

    public function create(): Response
    {
        return Inertia::render('Register');
    }

    /**
     * Handle a new tenant registration.
     *
     * The user is logged in on the central domain and then redirected to the
     * new tenant subdomain. For the session to persist across the redirect,
     * the session cookie domain must be set to the parent domain, e.g.:
     * SESSION_DOMAIN=.wie-doet-wat.test
     */
    public function store(StoreTenantRegistrationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $tenant = $this->createTenant->handle($validated['subdomain']);

        $user = $this->createTenantOwner->handle(
            $tenant,
            $validated['name'],
            $validated['email'],
            $validated['password'],
        );

        Auth::login($user);

        return redirect()->to(
            str_replace(
                '://'.$request->getHost(),
                '://'.$validated['subdomain'].'.'.$request->getHost(),
                $request->getSchemeAndHttpHost(),
            )
        );
    }
}
