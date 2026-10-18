<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->cascadeOnDelete();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->string('resume_path');
            $table->string('original_filename');
            $table->string('resume_mime_type');
            $table->unsignedBigInteger('resume_size_bytes');
            $table->string('resume_fingerprint', 64);
            $table->string('status')->default('pending')->index();
            $table->string('review_status')->default('pending_review')->index();
            $table->timestamp('submitted_at');
            $table->timestamps();

            $table->unique(['job_id', 'candidate_id']);
            $table->index(['job_id', 'status']);
            $table->index('resume_fingerprint');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
