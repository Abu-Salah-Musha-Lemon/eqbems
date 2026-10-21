<?php

namespace App\Http\Controllers;

use App\Http\Requests\Institution\StoreInstitutionRequest;
use App\Http\Requests\Institution\UpdateInstitutionRequest;
use App\Models\Institution;
use App\Services\InstitutionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class InstitutionController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        private readonly InstitutionService $institutionService
    ) {}

    /**
     * Display a listing of institutions.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Institution::class);

        $institutions = $this->institutionService->paginate(10, auth()->user());
        // $institutions = $this->institutionService;

        return view(
            'institutions.index',
            compact('institutions')
        );
    }

    public function trashed(): View
    {
        $this->authorize('restore', new Institution);
        return view('institutions.trashed', [
            'institutions' => Institution::onlyTrashed()->latest('deleted_at')->paginate(15),
        ]);
    }

    public function restore(string $uuid): RedirectResponse
    {
        $institution = Institution::onlyTrashed()->where('uuid', $uuid)->firstOrFail();
        $this->authorize('restore', $institution);
        $institution->restore();
        return redirect()->route('institutions.trashed')->with('success', 'Institution restored.');
    }

    /**
     * Show the form for creating a new institution.
     */
    public function create(): View
        {
            $this->authorize('create', Institution::class);

            return view('institutions.create');
        }

    /**
     * Store a newly created institution.
     */
    public function store(
        StoreInstitutionRequest $request
    ): RedirectResponse {

        $this->authorize(
            'create',
            Institution::class
        );

        $this->institutionService
            ->create(array_merge($request->validated(), ['created_by' => auth()->id(), 'updated_by' => auth()->id()]));

        return redirect()
            ->route('institutions.index')
            ->with('success', 'Institution created successfully.');
    }
    /**
     * Display the specified institution.
     */
    public function show(Institution $institution): View
    {
        $this->authorize('view', $institution);
        return view('institutions.show', compact('institution'));
    }

    /**
     * Show the form for editing the specified institution.
     */
    public function edit(Institution $institution): View
    {
        $this->authorize(
            'update',
            $institution
        );
        return view('institutions.edit', compact('institution'));
    }

    /**
     * Update the specified institution.
     */
        public function update(
            UpdateInstitutionRequest $request,
            Institution $institution
        ): RedirectResponse {

            $this->authorize('update', $institution);

            $this->institutionService->update(
                $institution,
                array_merge($request->validated(), ['updated_by' => auth()->id()])
            );

            return redirect()
                ->route('institutions.index')
                ->with('success', 'Institution updated successfully.');
        }

    /**
     * Remove the specified institution.
     */
   public function destroy(Institution $institution): RedirectResponse
    {
        $this->authorize('delete', $institution);

        $this->institutionService->delete($institution);

        return redirect()
            ->route('institutions.index')
            ->with('success', 'Institution deleted successfully.');
    }
}