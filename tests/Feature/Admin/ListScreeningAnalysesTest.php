<?php

namespace Tests\Feature\Admin;

use App\Filament\Resources\ScreeningAnalyses\Pages\ListScreeningAnalyses;
use App\Models\ScreeningAnalysis;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ListScreeningAnalysesTest extends TestCase
{
    use RefreshDatabase;

    public function test_screening_analyses_page_renders_boolean_state_and_errors(): void
    {
        $user = User::factory()->admin()->create();

        ScreeningAnalysis::factory()->failed('gemini_error')->create([
            'error_message' => 'Gemini request failed.',
        ]);

        Livewire::actingAs($user)
            ->test(ListScreeningAnalyses::class)
            ->assertSuccessful()
            ->assertSee(__('Error Message'))
            ->assertSee('Gemini request failed.');
    }
}
