<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/admin/applications/{application}/resume/download', [\App\Http\Controllers\Admin\ApplicationResumeController::class, 'download'])
    ->middleware(['auth'])
    ->name('admin.applications.resume.download');
Route::get('/admin/experiments/{experiment}/export', [\App\Http\Controllers\Admin\ExperimentExportController::class, 'exportCsv'])
    ->middleware(['auth'])
    ->name('admin.experiments.export');
