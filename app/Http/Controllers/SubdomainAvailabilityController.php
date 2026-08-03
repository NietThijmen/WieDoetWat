<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SubdomainAvailabilityController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'subdomain' => [
                    'required',
                    'string',
                    'min:3',
                    'max:63',
                    'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                    Rule::unique('domains', 'domain'),
                ],
            ]);
        } catch (ValidationException $exception) {
            return response()->json(['available' => false], 400);
        }

        return response()->json(['available' => true]);
    }
}
