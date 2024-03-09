<?php

use App\Http\Controllers\DeployController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatusController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [\App\Http\Controllers\RequestController::class, 'home']);

Route::domain('{id}.vercel-local.com')->group(function () {
    // Define other routes that should match the subdomain here
    Route::get('/{file}', [\App\Http\Controllers\RequestController::class, 'request'])
        ->where('file', '.*'); // The '.*' is a wildcard to match any file path
});

