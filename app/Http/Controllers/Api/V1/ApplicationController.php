<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\DuplicateApplicationException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreApplicationRequest;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Job;
use App\Services\Resumes\ResumeStorageService;
use App\Services\Screening\ScreeningService;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function store(
        StoreApplicationRequest $request,
        Job $job,
        ResumeStorageService $resumeStorage,
        ScreeningService $screeningService,
    ): JsonResponse {
        abort_unless($job->isAcceptingApplications(), 404);

        $email = strtolower(trim($request->string('email')->toString()));

        $alreadyApplied = Candidate::query()
            ->where('email', $email)
            ->whereHas('applications', fn ($builder) => $builder->where('job_id', $job->id))
            ->exists();

        if ($alreadyApplied) {
            throw new DuplicateApplicationException;
        }

        $storedResume = $resumeStorage->store($request->file('resume'), $job);

        try {
            $application = DB::transaction(function () use ($request, $job, $storedResume, $email): Application {
                $candidate = Candidate::findOrCreateWithEmail(
                    $request->string('name')->toString(),
                    $email,
                    $request->filled('phone') ? $request->string('phone')->toString() : null,
                );

                return Application::query()->create([
                    'job_id' => $job->id,
                    'candidate_id' => $candidate->id,
                    'resume_path' => $storedResume['path'],
                    'original_filename' => $storedResume['original_filename'],
                    'resume_mime_type' => $storedResume['mime_type'],
                    'resume_size_bytes' => $storedResume['size_bytes'],
                    'resume_fingerprint' => $storedResume['fingerprint'],
                    'status' => \App\Enums\ApplicationStatus::Pending->value, 'review_status' => \App\Enums\ReviewStatus::PendingReview->value, 'submitted_at' => now(),
                ]);
            });
        } catch (QueryException $exception) {
            $resumeStorage->delete($storedResume['path']);

            if ($this->isUniqueConstraintViolation($exception)) {
                throw new DuplicateApplicationException;
            }

            throw $exception;
        }

        $screeningService->queueScreening($application);

        return response()->json([
            'data' => [
                'id' => $application->id,
                'status' => $application->status->value,
                'message' => 'Your application has been submitted successfully.',
            ],
        ], 201);
    }

    private function isUniqueConstraintViolation(QueryException $exception): bool
    {
        $code = (string) $exception->getCode();

        return in_array($code, ['23000', '23505'], true);
    }
}
