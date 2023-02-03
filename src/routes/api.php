<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')
    ->middleware('auth.api.server')
    ->group(base_path('routes/api/v1/v1.php'));

Route::prefix('v2')
    ->middleware('auth.api')
    ->group(base_path('routes/api/v2/v2.php'));
