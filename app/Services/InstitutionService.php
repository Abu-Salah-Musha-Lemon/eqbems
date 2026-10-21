<?php

namespace App\Services;

use App\Models\Institution;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InstitutionService
{
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Institution::query()
            ->latest()
            ->paginate($perPage);
    }

    /**
     * List soft-deleted institutions.
     */
    public function trashed(int $perPage = 10): LengthAwarePaginator
    {
        return Institution::onlyTrashed()
            ->latest('deleted_at')
            ->paginate($perPage);
    }

    public function create(array $data): Institution
    {
        return DB::transaction(function () use ($data) {
            if (isset($data['logo'])) {
                $data['logo'] = $data['logo']->store(
                    'institutions',
                    'public'
                );
            }

            return Institution::create($data);
        });
    }

    public function update(
        Institution $institution,
        array $data
    ): Institution {
        return DB::transaction(function () use ($institution, $data) {
            if (isset($data['logo'])) {
                $oldLogo = $institution->logo;

                $data['logo'] = $data['logo']->store(
                    'institutions',
                    'public'
                );

                if ($oldLogo) {
                    Storage::disk('public')->delete($oldLogo);
                }
            }

            $institution->update($data);

            return $institution->refresh();
        });
    }

    /**
     * Soft delete an institution.
     */
    public function delete(Institution $institution): void
    {
        $institution->delete();
    }

    /**
     * Restore a soft-deleted institution.
     */
    public function restore(string $uuid): Institution
    {
        $institution = Institution::onlyTrashed()
            ->where('uuid', $uuid)
            ->firstOrFail();

        $institution->restore();

        return $institution->fresh();
    }

    /**
     * Permanently delete an institution.
     */
    public function forceDelete(string $uuid): void
    {
        $institution = Institution::onlyTrashed()
            ->where('uuid', $uuid)
            ->firstOrFail();

        DB::transaction(function () use ($institution) {
            if ($institution->logo) {
                Storage::disk('public')->delete($institution->logo);
            }

            $institution->forceDelete();
        });
    }

    public function toggleStatus(Institution $institution): Institution
    {
        $institution->status = ! $institution->status;
        $institution->save();

        return $institution->fresh();
    }
}