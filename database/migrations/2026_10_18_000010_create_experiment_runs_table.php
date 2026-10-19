<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('experiment_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experiment_id')->constrained('evaluation_experiments')->cascadeOnDelete();
            $table->unsignedBigInteger('job_id')->nullable();
            $table->unsignedBigInteger('application_id')->nullable();
            $table->unsignedBigInteger('screening_analysis_id')->nullable();
            $table->unsignedInteger('run_index')->default(1);
            $table->string('status');
            $table->unsignedSmallInteger('score')->nullable();
            $table->boolean('eligible')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->string('model_name')->nullable();
            $table->string('prompt_version')->nullable();
            $table->json('criteria_snapshot')->nullable();
            $table->string('input_fingerprint', 64)->nullable();
            $table->string('error_code')->nullable();
            $table->timestamps();

            $table->index(['experiment_id', 'run_index']);
            $table->index(['experiment_id', 'application_id']);
            $table->foreign('job_id')->references('id')->on('jobs')->nullOnDelete();
            $table->foreign('application_id')->references('id')->on('applications')->nullOnDelete();
            $table->foreign('screening_analysis_id')->references('id')->on('screening_analyses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experiment_runs');
    }
};
