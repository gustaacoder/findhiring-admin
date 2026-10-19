<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('human_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('reviewer_id')->nullable();
            $table->string('decision');
            $table->text('notes')->nullable();
            $table->timestamp('reviewed_at');
            $table->timestamps();

            $table->index(['application_id', 'decision']);
            $table->foreign('reviewer_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('human_reviews');
    }
};
