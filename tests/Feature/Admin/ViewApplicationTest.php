<?php

namespace Tests\Feature\Admin;

use App\Enums\ReviewDecision;
use App\Enums\ReviewStatus;
use App\Filament\Resources\Applications\Pages\ViewApplication;
use App\Models\Application;
use App\Models\HumanReview;
use App\Models\ScreeningAnalysis;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ViewApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_page_renders_reviews_and_processing_history(): void
    {
        $user = User::factory()->admin()->create();
        $application = Application::factory()->create();

        HumanReview::factory()->for($application)->for($user, 'reviewer')->create();
        ScreeningAnalysis::factory()->for($application)->completed(80, true)->create();

        Livewire::actingAs($user)
            ->test(ViewApplication::class, ['record' => $application->getRouteKey()])
            ->assertSuccessful()
            ->assertSee(__('Human Reviews'))
            ->assertSee(__('Processing History'));
    }

    public function test_recruiter_can_record_a_human_review(): void
    {
        $user = User::factory()->admin()->create();
        $application = Application::factory()->create();

        Livewire::actingAs($user)
            ->test(ViewApplication::class, ['record' => $application->getRouteKey()])
            ->callAction('review', [
                'decision' => ReviewDecision::Proceed->value,
                'notes' => 'Candidate meets the technical requirements.',
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('human_reviews', [
            'application_id' => $application->id,
            'reviewer_id' => $user->id,
            'decision' => ReviewDecision::Proceed->value,
        ]);
        $this->assertSame(ReviewStatus::Completed, $application->refresh()->review_status);
    }
}
