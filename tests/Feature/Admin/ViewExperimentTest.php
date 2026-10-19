<?php

namespace Tests\Feature\Admin;

use App\Filament\Resources\Experiments\Pages\ViewExperiment;
use App\Models\EvaluationExperiment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ViewExperimentTest extends TestCase
{
    use RefreshDatabase;

    public function test_experiment_page_renders_relationship_sections(): void
    {
        $user = User::factory()->admin()->create();
        $experiment = EvaluationExperiment::factory()->create();

        Livewire::actingAs($user)
            ->test(ViewExperiment::class, ['record' => $experiment->getRouteKey()])
            ->assertSuccessful()
            ->assertSee(__('Reference Evaluations'))
            ->assertSee(__('Experiment Runs'));
    }
}
