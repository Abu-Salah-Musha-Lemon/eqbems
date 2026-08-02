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
    // public function __construct(
    //     private readonly InstitutionService $institutionService
    // ) {}

    /**
     * Display a listing of institutions.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Institution::class);

        $institutions = $this->institutionService->paginate();

        return view(
            'institutions.index',
            compact('institutions')
        );
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
            ->create($request->validated());

        return redirect()
            ->route('institutions.index')
            ->with('success', 'Institution created successfully.');
    }
    /**
     * Display the specified institution.
     */
    public function show(Institution $institution): View
    {
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
                $request->validated()
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