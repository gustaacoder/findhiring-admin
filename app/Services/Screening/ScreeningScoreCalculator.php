<?php

namespace App\Services\Screening;

use App\DTO\ScoreResult;
use App\Models\JobCriterion;
use Illuminate\Support\Collection;

class ScreeningScoreCalculator
{
    /**
     * @param  Collection<int, JobCriterion>  $criteria
     * @param  list<int>  $matchedCriterionIds
     */
    public function calculate(Collection $criteria, array $matchedCriterionIds): ScoreResult
    {
        $matched = array_map('intval', $matchedCriterionIds);

        $score = 0;
        $awardedWeights = [];
        $allRequiredMatched = true;

        foreach ($criteria as $criterion) {
            $isMatched = in_array((int) $criterion->id, $matched, true);
            $awarded = $isMatched ? (int) $criterion->weight : 0;
            $awardedWeights[(int) $criterion->id] = $awarded;
            $score += $awarded;

            if ($criterion->isRequired() && ! $isMatched) {
                $allRequiredMatched = false;
            }
        }

        $score = max(0, min(100, $score));

        return new ScoreResult($score, $allRequiredMatched && $criteria->isNotEmpty(), $awardedWeights);
    }
}
