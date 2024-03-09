<?php

use App\Http\Controllers\StatusController;
use App\Http\Controllers\UploadAndDeployController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\DeployController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/upload/ls', [UploadController::class, 'ls']); // the test to see the content local `dep` folder.
// These two are deletion endpoint to delete `output` and `dist` directory on R2.
Route::post('/upload/delete', [UploadController::class, 'deleteOutput']);
Route::post('/upload/delete/dist', [UploadController::class, 'deleteDist']);

// Call the upload function.
Route::post('/upload', [UploadController::class, 'upload'])->name('upload');
// The endpoint to see status.
Route::post('/status', [StatusController::class, 'status'])->name('deployment-status');

