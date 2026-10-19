<?php

namespace Database\Seeders;

use Database\Seeders\Data\FictionalResumeCatalog;
use Database\Seeders\Support\FictionalResumeImporter;
use Illuminate\Database\Seeder;

class EvaluationDatasetSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AcademicJobMatrixSeeder::class);

        $importer = app(FictionalResumeImporter::class);
        $importer->import(FictionalResumeCatalog::baseProfiles());
    }
}
