<?php

namespace App\Http\Controllers;

use App\Http\Requests\Institution\StoreInstitutionRequest;
use App\Http\Requests\Institution\UpdateInstitutionRequest;
use App\Models\Institution;
use App\Services\InstitutionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InstitutionController extends Controller
{
    public function __construct(
        private readonly InstitutionService $institutionService
    ) {}

    /**
     * Display a listing of institutions.
     */
    public function index(): View
    {
        $institutions = Institution::latest()->paginate(10);

        return view('institutions.index', compact('institutions'));
    }

    /**
     * Show the form for creating a new institution.
     */
    public function create(): View
    {
        return view('institutions.create');
    }

    /**
     * Store a newly created institution.
     */
    public function store(StoreInstitutionRequest $request): RedirectResponse
    {
        $this->institutionService->create(
            $request->validated()
        );

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
        return view('institutions.edit', compact('institution'));
    }

    /**
     * Update the specified institution.
     */
    public function update(
        UpdateInstitutionRequest $request,
        Institution $institution
    ): RedirectResponse {

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
    public function destroy(
        Institution $institution
    ): RedirectResponse {

        $this->institutionService->delete($institution);

        return redirect()
            ->route('institutions.index')
            ->with('success', 'Institution deleted successfully.');
    }
}