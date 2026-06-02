<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Health Check Endpoint
|--------------------------------------------------------------------------
|
| This endpoint is used to verify that the application and its critical
| dependencies are operational.
|
| Checks:
| - Application availability
| - Database connectivity
| - Redis connectivity
|
| Response Example:
| {
|     "status": "ok",
|     "application": "running",
|     "database": "connected",
|     "redis": "connected"
| }
|
*/
Route::get('/health', function () {
    /**
     * Verify database connectivity.
     */
    try {
        DB::connection()->getPdo();

        $database = 'connected';
    } catch (\Throwable $e) {
        $database = 'error: ' . $e->getMessage();
    }

    /**
     * Verify Redis connectivity.
     */
    try {
        Redis::ping();

        $redis = 'connected';
    } catch (\Throwable $e) {
        $redis = 'error: ' . $e->getMessage();
    }

    /**
     * Return health status response.
     */
    return response()->json([
        'status'      => 'ok',
        'application' => 'running',
        'database'    => $database,
        'redis'       => $redis,
    ]);
});

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
|
| Routes that do not require authentication.
|
*/

require __DIR__ . '/api/login.php';

/*
|--------------------------------------------------------------------------
| Protected API Routes (V1)
|--------------------------------------------------------------------------
|
| All routes inside this group require Sanctum authentication.
|
*/

Route::prefix('v1')
    ->middleware('auth:sanctum')
    ->group(function () {

        require __DIR__ . '/api/v1/auth.php';
        require __DIR__ . '/api/v1/staff.php';

        // Future modules
        // require __DIR__ . '/api/v1/patients.php';
        // require __DIR__ . '/api/v1/appointments.php';
        // require __DIR__ . '/api/v1/consultations.php';
        // require __DIR__ . '/api/v1/prescriptions.php';
        // require __DIR__ . '/api/v1/invoices.php';
        // require __DIR__ . '/api/v1/pharmacy.php';
    });