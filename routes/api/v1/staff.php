<?php

use App\Http\Controllers\Staff\StaffUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Staff Routes
|--------------------------------------------------------------------------
|
| Prefix inherited from:
| /api/v1
|
| Middleware inherited from:
| auth:sanctum
|
| Endpoints:
| - GET /staff/users
|
*/

Route::prefix('staff')->group(function () {

    /**
     * Retrieve staff users belonging to the authenticated
     * user's tenant.
     */
    Route::get('/users', [StaffUserController::class, 'index']);
});