<?php

use App\Http\Controllers\SubdomainAvailabilityController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/subdomain/check', SubdomainAvailabilityController::class)->name('subdomain.check');


require_once __DIR__.'/tenant.php';
