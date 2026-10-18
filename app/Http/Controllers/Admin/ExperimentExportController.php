<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EvaluationExperiment;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExperimentExportController extends Controller
{
    public function exportCsv(EvaluationExperiment $experiment): StreamedResponse
    {
        abort_unless(auth()->check(), 403);

        $filename = 'experiment-'.$experiment->id.'-'.date('YmdHis').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($experiment): void {
            $handle = fopen('php://output', 'wb');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Experiment',
                'Application ID',
                'Job',
                'Candidate',
                'Run Index',
                'Status',
                'AI Score',
                'AI Eligible',
                'Duration (ms)',
                'Model',
                'Prompt Version',
                'Error Code',
                'Created At',
            ]);

            $runs = $experiment->runs()
                ->with(['application.job', 'application.candidate'])
                ->orderBy('run_index')
                ->get();

            foreach ($runs as $run) {
                fputcsv($handle, [
                    $experiment->name,
                    (string) $run->application_id,
                    $run->job?->title ?? $run->application?->job?->title ?? '',
                    $run->application?->candidate?->name ?? '',
                    (string) $run->run_index,
                    $run->status->value,
                    $run->score !== null ? (string) $run->score : '',
                    $run->eligible !== null ? ($run->eligible ? 'true' : 'false') : '',
                    $run->duration_ms !== null ? (string) $run->duration_ms : '',
                    $run->model_name ?? '',
                    $run->prompt_version ?? '',
                    $run->error_code ?? '',
                    $run->created_at?->toIso8601String() ?? '',
                ]);
            }

            fclose($handle);
        };

        return new StreamedResponse($callback, Response::HTTP_OK, $headers);
    }
}
