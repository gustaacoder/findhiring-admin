<?php

namespace App\Services\Ranking;

use App\Enums\ScreeningStatus;
use App\Models\Application;
use App\Models\Job;
use Illuminate\Database\Eloquent\Collection;

class CandidateRankingService
{
    public function rankedApplications(Job $job): Collection
    {
        return $job->applications()
            ->with(['candidate', 'currentAnalysis', 'latestReview'])
            ->whereHas('currentAnalysis', fn ($query) => $query->where('status', ScreeningStatus::Completed))
            ->get()
            ->sortBy(fn (Application $application): array => [
                $application->currentAnalysis?->eligible ? 0 : 1,
                -((int) ($application->currentAnalysis?->score ?? 0)),
                $application->submitted_at->getTimestamp(),
                (int) $application->id,
            ])
            ->values();
    }

    /**
     * @return array<int, int> application id to one-based rank position
     */
    public function rankingPositions(Job $job): array
    {
        $positions = [];

        foreach ($this->rankedApplications($job) as $index => $application) {
            $positions[(int) $application->id] = $index + 1;
        }

        return $positions;
    }

    public function rankOf(Application $application): ?int
    {
        return $this->rankingPositions($application->job)[$application->id] ?? null;
    }
}
