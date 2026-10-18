<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reference_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experiment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('evaluator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('criterion_results');
            $table->unsignedSmallInteger('score');
            $table->boolean('eligible');
            $table->text('notes')->nullable();
            $table->boolean('is_consolidated')->default(false);
            $table->timestamps();

            $table->index(['experiment_id', 'application_id', 'is_consolidated']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reference_evaluations');
    }
};
