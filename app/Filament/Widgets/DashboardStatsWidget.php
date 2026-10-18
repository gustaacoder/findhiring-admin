<?php

namespace App\Filament\Widgets;

use App\Enums\ApplicationStatus;
use App\Enums\JobStatus;
use App\Enums\ReviewStatus;
use App\Enums\ScreeningStatus;
use App\Models\Application;
use App\Models\Job;
use App\Models\ScreeningAnalysis;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $averageDuration = ScreeningAnalysis::query()
            ->where('status', ScreeningStatus::Completed)
            ->whereNotNull('duration_ms')
            ->avg('duration_ms');

        $averageSeconds = $averageDuration !== null ? round($averageDuration / 1000, 2).' s' : '—';

        return [
            Stat::make('Total Jobs', (string) Job::query()->count()),
            Stat::make('Published Jobs', (string) Job::query()->where('status', JobStatus::Published)->count()),
            Stat::make('Total Applications', (string) Application::query()->count()),
            Stat::make('Pending Screenings', (string) Application::query()->where('status', ApplicationStatus::Pending)->count()),
            Stat::make('Completed Screenings', (string) ScreeningAnalysis::query()->where('status', ScreeningStatus::Completed)->where('is_current', true)->count()),
            Stat::make('Failed Screenings', (string) ScreeningAnalysis::query()->where('status', ScreeningStatus::Failed)->count()),
            Stat::make('Pending Review', (string) Application::query()->where('review_status', ReviewStatus::PendingReview)->count()),
            Stat::make('Average Processing Time', $averageSeconds),
        ];
    }
}
