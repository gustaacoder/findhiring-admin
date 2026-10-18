<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('screening_criterion_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('screening_analysis_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_criterion_id')->constrained()->nullOnDelete();
            $table->boolean('matched')->default(false);
            $table->text('evidence')->nullable();
            $table->text('reason')->nullable();
            $table->unsignedSmallInteger('awarded_weight')->default(0);
            $table->timestamps();

            $table->unique(['screening_analysis_id', 'job_criterion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screening_criterion_results');
    }
};
