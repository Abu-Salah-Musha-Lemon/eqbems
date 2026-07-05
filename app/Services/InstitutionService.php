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
    return DB::transaction(function () use ($data) {

        if(isset($data['logo'])){

            $data['logo'] = $data['logo']->store(
                'institutions',
                'public'
            );

        }

        return Institution::create($data);

    });
}

    /**
     * Update an existing institution.
     */
public function update(
    Institution $institution,
    array $data
): Institution {

    return DB::transaction(function () use ($institution, $data) {

        if (
            isset($data['logo'])
        ) {

            if ($institution->logo) {

                Storage::disk('public')
                    ->delete($institution->logo);

            }

            $data['logo'] = $data['logo']
                ->store('institutions', 'public');
        }

        $institution->update($data);

        return $institution->refresh();

    });
}

    /**
     * Soft delete an institution.
     */
public function delete(
    Institution $institution
): void {

    $institution->delete();

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