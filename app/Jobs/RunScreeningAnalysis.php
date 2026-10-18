<?php

namespace App\Jobs;

use App\Models\ScreeningAnalysis;
use App\Services\Screening\ScreeningService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RunScreeningAnalysis implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries;

    /** @var list<int> */
    public array $backoff = [10, 30, 60];

    public int $uniqueFor = 600;

    public function __construct(public readonly int $analysisId)
    {
        $this->tries = max(1, (int) config('findhiring.gemini.max_attempts', 3));
    }

    public function uniqueId(): string
    {
        return 'screening-analysis-'.$this->analysisId;
    }

    public function handle(ScreeningService $screeningService): void
    {
        $analysis = ScreeningAnalysis::find($this->analysisId);

        if ($analysis === null) {
            return;
        }

        $screeningService->execute($analysis);
    }
}
