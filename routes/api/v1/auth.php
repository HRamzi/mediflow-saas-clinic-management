<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Identity Routes
|--------------------------------------------------------------------------
|
| Authenticated identity endpoints.
|
| Prefix inherited from:
| /api/v1
|
| Middleware inherited from:
| auth:sanctum
|
*/

Route::prefix('auth')->group(function () {

    /**
     * Revoke the current access token.
     */
    Route::post('/logout', [AuthController::class, 'logout']);

    /**
     * Retrieve the authenticated user.
     */
    Route::get('/me', [AuthController::class, 'me']);
});