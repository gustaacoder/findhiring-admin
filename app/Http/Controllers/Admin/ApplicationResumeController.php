<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Support\Facades\Storage;

class ApplicationResumeController extends Controller
{
    public function download(Application $application)
    {
        abort_unless(auth()->check(), 403);

        $disk = (string) config('findhiring.screening.resume_disk', 'local');
        $path = $application->resume_path;

        if (! Storage::disk($disk)->exists($path)) {
            abort(404);
        }

        return Storage::disk($disk)->download($path, $application->original_filename);
    }
}
