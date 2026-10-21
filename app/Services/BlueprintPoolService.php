<?php

namespace App\Services;

use App\Models\Blueprint;
use App\Models\QuestionVersion;
use Illuminate\Database\Eloquent\Builder;

/**
 * Single source of truth for questions eligible for blueprint generation.
 * Approved historical versions can be reused, but only the latest approved
 * version of each question may appear in a newly generated paper.
 */
class BlueprintPoolService
{
    public function forBlueprint(Blueprint $blueprint): Builder
    {
        return $this->query(
            (int) $blueprint->institution_id,
            (int) $blueprint->subject_id,
            $blueprint->type,
            $blueprint->difficulty,
            $blueprint->format,
        );
    }

    public function query(
        int $institutionId,
        int $subjectId,
        ?string $type = null,
        ?string $difficulty = null,
        ?string $format = null,
    ): Builder {
        $query = QuestionVersion::query()
            ->where('status', 'approved')
            ->whereHas('question', fn (Builder $q) => $q
                ->where('institution_id', $institutionId)
                ->where('subject_id', $subjectId))
            ->whereRaw("question_versions.version_number = (
                SELECT MAX(approved_versions.version_number)
                FROM question_versions AS approved_versions
                WHERE approved_versions.question_id = question_versions.question_id
                  AND approved_versions.status = 'approved'
            )");

        if ($type !== null && $type !== '') {
            $query->where('type', $type);
        }
        if ($difficulty !== null && $difficulty !== '') {
            $query->where('difficulty', $difficulty);
        }
        if ($format !== null && $format !== '') {
            $query->where('format', $format);
        }

        return $query;
    }
}
