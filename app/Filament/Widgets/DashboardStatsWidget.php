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
            Stat::make(__('Total Jobs'), (string) Job::query()->count()),
            Stat::make(__('Published Jobs'), (string) Job::query()->where('status', JobStatus::Published)->count()),
            Stat::make(__('Total Applications'), (string) Application::query()->count()),
            Stat::make(__('Pending Screenings'), (string) Application::query()->where('status', ApplicationStatus::Pending)->count()),
            Stat::make(__('Completed Screenings'), (string) ScreeningAnalysis::query()->where('status', ScreeningStatus::Completed)->where('is_current', true)->count()),
            Stat::make(__('Failed Screenings'), (string) ScreeningAnalysis::query()->where('status', ScreeningStatus::Failed)->count()),
            Stat::make(__('Pending Review'), (string) Application::query()->where('review_status', ReviewStatus::PendingReview)->count()),
            Stat::make(__('Average Processing Time'), $averageSeconds),
        ];
    }
}
