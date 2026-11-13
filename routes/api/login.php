<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Login Routes
|--------------------------------------------------------------------------
|
| Public authentication endpoints.
|
| Prefix:
| - /api/v1/auth
|
| Public Endpoints:
| - POST /login
|
| Security:
| - No authentication required.
|
*/

Route::prefix('v1/auth')->group(function () {

    /**
     * Authenticate a staff user and issue an access token.
     */
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,15');
});
