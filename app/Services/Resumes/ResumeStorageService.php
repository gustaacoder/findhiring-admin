<?php

namespace App\Services\Resumes;

use App\Models\Job;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class ResumeStorageService
{
    private const PDF_HEADER = '%PDF';

    /** @return array{path: string, original_filename: string, mime_type: string, size_bytes: int, fingerprint: string} */
    public function store(UploadedFile $file, Job $job): array
    {
        $mimeType = $this->resolveMimeType($file->getRealPath());

        if ($mimeType === null) {
            throw new UnsupportedResumeException;
        }

        $fingerprint = hash_file('sha256', $file->getRealPath());

        if ($fingerprint === false) {
            throw new UnsupportedResumeException;
        }

        $extension = $mimeType === 'application/pdf' ? 'pdf' : 'docx';
        $directory = (string) config('findhiring.screening.resume_directory', 'resumes');
        $path = $directory.'/'.$job->id.'/'.Str::uuid()->toString().'.'.$extension;

        Storage::disk((string) config('findhiring.screening.resume_disk', 'local'))
            ->put($path, (string) file_get_contents($file->getRealPath()), 'private');

        return [
            'path' => $path,
            'original_filename' => mb_substr($file->getClientOriginalName(), 0, 255),
            'mime_type' => $mimeType,
            'size_bytes' => (int) $file->getSize(),
            'fingerprint' => $fingerprint,
        ];
    }

    public function absolutePath(string $relativePath): string
    {
        return Storage::disk((string) config('findhiring.screening.resume_disk', 'local'))
            ->path($relativePath);
    }

    public function delete(string $relativePath): void
    {
        Storage::disk((string) config('findhiring.screening.resume_disk', 'local'))->delete($relativePath);
    }

    public function resolveMimeType(string $absolutePath): ?string
    {
        $handle = fopen($absolutePath, 'rb');

        if ($handle === false) {
            return null;
        }

        $header = (string) fread($handle, 4);
        fclose($handle);

        if (str_starts_with($header, self::PDF_HEADER)) {
            return 'application/pdf';
        }

        if (str_starts_with($header, "PK\x03\x04") && $this->isDocx($absolutePath)) {
            return 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        }

        return null;
    }

    private function isDocx(string $absolutePath): bool
    {
        $archive = new ZipArchive;

        if ($archive->open($absolutePath) !== true) {
            return false;
        }

        $hasDocument = $archive->locateName('word/document.xml') !== false;
        $hasContentTypes = $archive->locateName('[Content_Types].xml') !== false;
        $archive->close();

        return $hasDocument && $hasContentTypes;
    }
}
