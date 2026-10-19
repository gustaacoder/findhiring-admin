<?php

namespace Database\Seeders;

use App\Enums\CriterionType;
use App\Enums\JobStatus;
use App\Models\Job;
use App\Models\JobCriterion;
use App\Models\User;
use Illuminate\Database\Seeder;

class AcademicJobMatrixSeeder extends Seeder
{
    /** @return array<string, mixed> */
    public static function matrix(): array
    {
        return [
            [
                'title' => 'Back-end PHP/Laravel Developer',
                'slug' => 'back-end-php-laravel-developer',
                'description' => 'We are looking for a back-end developer to build and maintain web applications with PHP and Laravel. You will design relational models, deliver REST APIs and collaborate with front-end colleagues on product features.',
                'criteria' => [
                    ['Minimum 2 years of experience', 'At least two years of professional software development experience.', CriterionType::Required, 30, 'Explicit statements of professional development experience lasting two years or more.'],
                    ['PHP/Laravel', 'Professional experience developing web applications with PHP and the Laravel framework.', CriterionType::Required, 30, 'Explicit mention of Laravel used in a professional context. General PHP experience alone is not sufficient.'],
                    ['SQL databases', 'Practical experience designing and querying relational databases.', CriterionType::Preferred, 20, 'Mention of MySQL, PostgreSQL or similar relational databases used in projects or work.'],
                    ['REST APIs', 'Experience designing or consuming RESTful APIs.', CriterionType::Preferred, 20, 'Explicit mention of REST API development or integration work.'],
                ],
            ],
            [
                'title' => 'Front-end React Developer',
                'slug' => 'front-end-react-developer',
                'description' => 'We are hiring a front-end developer to build responsive single-page applications with React. You will integrate REST endpoints, collaborate through Git workflows and keep accessibility and code quality as daily standards.',
                'criteria' => [
                    ['React', 'Professional experience building user interfaces with the React library.', CriterionType::Required, 30, 'Explicit mention of React used to build applications or interfaces. Framework-adjacent experience such as Angular or Vue is not equivalent.'],
                    ['JavaScript or TypeScript', 'Solid practical knowledge of JavaScript or TypeScript.', CriterionType::Required, 30, 'Explicit mention of JavaScript or TypeScript in professional or substantial project experience.'],
                    ['REST API integration', 'Experience integrating front-end applications with REST APIs.', CriterionType::Preferred, 20, 'Mention of consuming REST endpoints, HTTP clients or API integration work.'],
                    ['Git/GitHub', 'Daily use of Git for version control, preferably in team workflows.', CriterionType::Preferred, 20, 'Mention of Git, GitHub or similar version control usage.'],
                ],
            ],
            [
                'title' => 'Junior Data Analyst',
                'slug' => 'junior-data-analyst',
                'description' => 'We are looking for a junior data analyst to transform operational data into clear reports and dashboards. You will write SQL queries, prepare analyses and communicate findings to non-technical stakeholders.',
                'criteria' => [
                    ['Minimum 1 year of data analysis experience', 'At least one year of professional experience working with data analysis activities.', CriterionType::Required, 30, 'Explicit statements of professional data analysis experience lasting one year or more.'],
                    ['SQL', 'Practical experience querying relational databases with SQL.', CriterionType::Required, 30, 'Explicit mention of SQL used to extract or manipulate data. Excel-only experience is not sufficient.'],
                    ['Python/Pandas', 'Experience using Python and the Pandas library for data manipulation.', CriterionType::Preferred, 20, 'Explicit mention of Python and Pandas used together for data work.'],
                    ['Power BI or equivalent BI tools', 'Experience building reports or dashboards with Power BI or equivalent business intelligence tools.', CriterionType::Preferred, 20, 'Mention of Power BI, Tableau, Looker Studio or similar BI tooling used to produce reports.'],
                ],
            ],
        ];
    }

    public function run(): void
    {
        $author = User::query()->orderBy('id')->first();

        foreach (self::matrix() as $jobDefinition) {
            $job = Job::query()->firstOrCreate(
                ['slug' => $jobDefinition['slug']],
                [
                    'title' => $jobDefinition['title'],
                    'description' => $jobDefinition['description'],
                    'status' => JobStatus::Published,
                    'published_at' => now()->subWeek(),
                    'created_by' => $author?->id,
                ],
            );

            if ($job->criteria()->exists()) {
                continue;
            }

            foreach ($jobDefinition['criteria'] as $index => [$name, $description, $type, $weight, $expectedEvidence]) {
                JobCriterion::query()->create([
                    'job_id' => $job->id,
                    'name' => $name,
                    'description' => $description,
                    'expected_evidence' => $expectedEvidence,
                    'type' => $type,
                    'weight' => $weight,
                    'sort_order' => $index + 1,
                ]);
            }
        }
    }
}
