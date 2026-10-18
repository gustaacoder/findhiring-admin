<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\ScreeningAnalysis;
use App\Services\Screening\ScreeningScoreCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_score_all_matched_is_100(): void
    {
        $this->seed(\Database\Seeders\EvaluationDatasetSeeder::class);
        $job = Job::where('slug', 'back-end-php-laravel-developer')->first();
        $application = $job->applications()->first();
        $analysis = ScreeningAnalysis::factory()->create(['application_id' => $application->id]);
        $analysis->forceFill(['status' => \App\Enums\ScreeningStatus::Processing])->save();

        $calculator = app(ScreeningScoreCalculator::class);
        $criteria = $job->criteria;
        $matched = $criteria->pluck('id')->toArray();
        $result = $calculator->calculate($criteria, $matched);

        $this->assertEquals(100, $result->score);
        $this->assertTrue($result->eligible);
    }
}
