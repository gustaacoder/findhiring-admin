<?php

namespace Database\Seeders;

use Database\Seeders\Data\FictionalResumeCatalog;
use Database\Seeders\Support\FictionalResumeImporter;
use Illuminate\Database\Seeder;

class ExpandedDatasetSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(EvaluationDatasetSeeder::class);

        $importer = app(FictionalResumeImporter::class);
        $importer->import(FictionalResumeCatalog::expandedProfiles());
    }
}
