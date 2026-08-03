<?php

namespace App\Actions\Fortify;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        $existingTenant = tenant();

        $subdomainRules = [
            'string',
            'min:3',
            'max:63',
            'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            Rule::unique('domains', 'domain'),
        ];

        if (! $existingTenant) {
            $subdomainRules[] = 'required';
        }

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'subdomain' => $subdomainRules,
        ])->validate();

        $tenant = $existingTenant ?? DB::transaction(function () use ($input) {
            $tenant = Tenant::create([
                'id' => $input['subdomain'],
            ]);

            $tenant->domains()->create([
                'domain' => $input['subdomain'],
            ]);

            return $tenant;
        });

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'tenant_id' => $tenant->id,
        ]);
    }
}
