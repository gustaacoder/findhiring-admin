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
            $table->foreignId('experiment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('application_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('screening_analysis_id')->nullable()->constrained()->nullOnDelete();
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
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experiment_runs');
    }
};
