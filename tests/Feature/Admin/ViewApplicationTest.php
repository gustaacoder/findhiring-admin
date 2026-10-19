<?php

namespace Tests\Feature\Admin;

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
}
