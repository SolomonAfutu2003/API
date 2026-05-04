<?php

use App\Http\Controllers\API\v1\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('v1')->group(function () {
        Route::apiResource('tasks', TaskController::class);
    });
});

require __DIR__ . '/auth.php';
