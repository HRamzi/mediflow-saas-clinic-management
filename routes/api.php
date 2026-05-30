<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        $database = 'connected';
    } catch (\Exception $e) {
        $database = 'error: ' . $e->getMessage();
    }

    try {
        Redis::ping();
        $redis = 'connected';
    } catch (\Exception $e) {
        $redis = 'error: ' . $e->getMessage();
    }


    return response()->json([
        'status' => 'ok',
        'application' => 'running',
        'database' => $database,
        'redis' => $redis,
    ]);
});
