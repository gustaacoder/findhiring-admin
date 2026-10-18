<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('expected_evidence')->nullable();
            $table->string('type')->default('required');
            $table->unsignedSmallInteger('weight');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['job_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_criteria');
    }
};
