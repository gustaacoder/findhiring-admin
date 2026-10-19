<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\JobDetailResource;
use App\Http\Resources\Api\V1\JobResource;
use App\Models\Job;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Job::query()
            ->published()
            ->orderByDesc('published_at')
            ->orderBy('id');

        $search = trim((string) $request->query('search'));

        if ($search !== '') {
            $query->where(function ($builder) use ($search): void {
                $builder->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        $perPage = min(max((int) $request->query('per_page', 12), 1), 50);

        return JobResource::collection($query->paginate($perPage));
    }

    public function show(Job $job): JsonResponse
    {
        abort_unless($job->isAcceptingApplications(), 404);

        $job->load('criteria');

        return (new JobDetailResource($job))->response();
    }
}
