<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('screening_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('queued')->index();
            $table->unsignedSmallInteger('score')->nullable();
            $table->boolean('eligible')->nullable();
            $table->text('summary')->nullable();
            $table->string('model_name')->nullable();
            $table->string('prompt_version');
            $table->json('criteria_snapshot')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('error_code')->nullable();
            $table->text('error_message')->nullable();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->boolean('is_current')->default(false)->index();
            $table->string('input_fingerprint', 64)->nullable();
            $table->timestamps();

            $table->index(['application_id', 'is_current']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screening_analyses');
    }
};
