<?php

namespace Database\Seeders\Support;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\Job;
use App\Services\Resumes\DocxResumeWriter;

class FictionalResumeImporter
{
    public function __construct(private readonly DocxResumeWriter $documentWriter) {}

    /**
     * @param  list<array{code: string, job_slug: string, name: string, email: string, phone: string, paragraphs: list<string>}>  $profiles
     */
    public function import(array $profiles): int
    {
        $imported = 0;

        foreach ($profiles as $profile) {
            $job = Job::query()->where('slug', $profile['job_slug'])->first();

            if ($job === null) {
                continue;
            }

            $candidate = Candidate::findOrCreateWithEmail($profile['name'], $profile['email'], $profile['phone']);

            $alreadyImported = Application::query()
                ->where('job_id', $job->id)
                ->where('candidate_id', $candidate->id)
                ->exists();

            if ($alreadyImported) {
                continue;
            }

            $relativePath = 'resumes/dataset/'.$job->id.'/'.$profile['code'].'.docx';
            $absolutePath = storage_path('app/private/'.$relativePath);

            $this->documentWriter->write($profile['paragraphs'], $absolutePath);

            Application::query()->create([
                'job_id' => $job->id,
                'candidate_id' => $candidate->id,
                'resume_path' => $relativePath,
                'original_filename' => $profile['code'].'-resume.docx',
                'resume_mime_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'resume_size_bytes' => (int) filesize($absolutePath),
                'resume_fingerprint' => hash_file('sha256', $absolutePath) ?: '',
                'submitted_at' => now()->subDays((crc32($profile['code']) % 20) + 1),
            ]);

            $imported++;
        }

        return $imported;
    }
}
