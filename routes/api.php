<?php

use App\Http\Controllers\Api\V1\ApplicationController;
use App\Http\Controllers\Api\V1\JobController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware('throttle:api')
    ->group(function (): void {
        Route::get('/jobs', [JobController::class, 'index']);
        Route::get('/jobs/{job:slug}', [JobController::class, 'show']);
        Route::post('/jobs/{job:slug}/applications', [ApplicationController::class, 'store'])
            ->middleware('throttle:applications');
    });
