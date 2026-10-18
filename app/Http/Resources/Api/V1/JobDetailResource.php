<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;

class JobDetailResource extends JobResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'requirements' => [
                'required' => $this->criteriaCollection('required'),
                'preferred' => $this->criteriaCollection('preferred'),
            ],
        ]);
    }

    /** @return list<array<string, mixed>> */
    private function criteriaCollection(string $type): array
    {
        return $this->resource->criteria
            ->filter(fn ($criterion): bool => $criterion->type->value === $type)
            ->map(fn ($criterion): array => [
                'name' => $criterion->name,
                'description' => $criterion->description,
                'expected_evidence' => $criterion->expected_evidence,
            ])
            ->values()
            ->all();
    }
}
