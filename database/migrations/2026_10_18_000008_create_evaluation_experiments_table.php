<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_experiments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('dataset_identifier');
            $table->json('criteria_snapshot')->nullable();
            $table->string('model_name')->nullable();
            $table->string('prompt_version')->nullable();
            $table->json('processing_config')->nullable();
            $table->string('status')->default('draft')->index();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_experiments');
    }
};
