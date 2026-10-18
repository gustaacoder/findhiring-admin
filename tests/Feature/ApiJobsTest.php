<?php

namespace Tests\Feature;

use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiJobsTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_jobs_index_returns_published_jobs(): void
    {
        $this->seed(\Database\Seeders\EvaluationDatasetSeeder::class);

        $response = $this->getJson('/api/v1/jobs');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'links', 'meta']);
    }

    public function test_job_show_returns_404_for_draft(): void
    {
        $draft = Job::factory()->create();

        $this->getJson('/api/v1/jobs/'.$draft->slug)
            ->assertStatus(404);
    }
}
