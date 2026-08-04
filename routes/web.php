<?php

use App\Http\Controllers\SubdomainAvailabilityController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        Route::get('/', function () {
            return Inertia::render('Home');
        });

        Route::get('/subdomain/check', SubdomainAvailabilityController::class)->name('subdomain.check');
    });
}
