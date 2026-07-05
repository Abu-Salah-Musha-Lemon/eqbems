<?php

namespace App\Services;

use App\Models\Institution;

class InstitutionService
{
    /**
     * Create a new institution.
     */
    public function create(array $data): Institution
    {
        return Institution::create($data);
    }

    /**
     * Update an existing institution.
     */
    public function update(Institution $institution, array $data): Institution
    {
        $institution->update($data);

        return $institution->fresh();
    }

    /**
     * Soft delete an institution.
     */
    public function delete(Institution $institution): bool
    {
        return $institution->delete();
    }

    /**
     * Restore a soft-deleted institution.
     */
    public function restore(int $id): Institution
    {
        $institution = Institution::withTrashed()->findOrFail($id);

        $institution->restore();

        return $institution;
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus(Institution $institution): Institution
    {
        $institution->status = ! $institution->status;
        $institution->save();

        return $institution->fresh();
    }
}