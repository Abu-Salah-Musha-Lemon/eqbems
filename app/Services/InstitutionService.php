<?php

namespace App\Services;

use App\Models\Institution;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InstitutionService
{
    public function paginate(int $perPage = 10, ?\App\Models\User $user = null)
    {
        return \App\Support\AccessScope::restrict(Institution::query(), $user ?? auth()->user(), 'id')
            ->latest()
            ->paginate($perPage);
    }
    
    public function create(array $data): Institution
    {
        return DB::transaction(function () use ($data) {

            if (isset($data['logo'])) {
                $data['logo'] = $data['logo']->store('institutions', 'public');
            }

            return Institution::create($data);
        });
    }

    public function update(Institution $institution, array $data): Institution
    {
        return DB::transaction(function () use ($institution, $data) {

            if (isset($data['logo'])) {

                if ($institution->logo) {
                    Storage::disk('public')->delete($institution->logo);
                }

                $data['logo'] = $data['logo']->store('institutions', 'public');
            }

            $institution->update($data);

            return $institution->refresh();
        });
    }

    public function delete(Institution $institution): void
    {
        $institution->delete();
    }

    public function restore(int $id): Institution
    {
        $institution = Institution::withTrashed()->findOrFail($id);
        $institution->restore();

        return $institution;
    }

    public function toggleStatus(Institution $institution): Institution
    {
        $institution->status = ! $institution->status;
        $institution->save();

        return $institution->fresh();
    }
}