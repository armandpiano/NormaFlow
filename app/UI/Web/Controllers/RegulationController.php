<?php

declare(strict_types=1);

namespace App\UI\WEB\Controllers;

use App\Application\Services\RegulationService;
use App\UI\WEB\Requests\StoreRegulationRequest;
use App\UI\WEB\Requests\UpdateRegulationRequest;
use App\UI\WEB\Requests\StoreRequirementRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class RegulationController extends Controller
{
    public function __construct(
        private readonly RegulationService $regulationService
    ) {}

    /**
     * Display list of regulations
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', \App\Models\Regulation::class);

        $regulations = $this->regulationService->getAllRegulations();

        // Filter by type if provided
        if ($request->has('type') && $request->type) {
            $regulations = $this->regulationService->getRegulationsByType($request->type);
        }

        // Search if provided
        if ($request->has('search') && $request->search) {
            $regulations = $this->regulationService->searchRegulations($request->search);
        }

        return view('regulations.index', [
            'regulations' => $regulations,
            'filters' => $request->only(['type', 'search']),
        ]);
    }

    /**
     * Show regulation details
     */
    public function show(Request $request, string $id): View
    {
        $regulation = $this->regulationService->getRegulation($id);
        
        if (!$regulation) {
            abort(404);
        }

        Gate::authorize('view', $regulation);

        $requirements = $this->regulationService->getRequirementsByRegulation($id);

        return view('regulations.show', [
            'regulation' => $regulation,
            'requirements' => $requirements,
        ]);
    }

    /**
     * Show create form
     */
    public function create(): View
    {
        Gate::authorize('create', \App\Models\Regulation::class);

        return view('regulations.create');
    }

    /**
     * Store new regulation
     */
    public function store(StoreRegulationRequest $request): RedirectResponse
    {
        Gate::authorize('create', \App\Models\Regulation::class);

        $regulation = $this->regulationService->createRegulation($request->validated());

        return redirect()
            ->route('regulations.show', $regulation->id->toString())
            ->with('success', 'Normatividad creada correctamente.');
    }

    /**
     * Show edit form
     */
    public function edit(Request $request, string $id): View
    {
        $regulation = $this->regulationService->getRegulation($id);
        
        if (!$regulation) {
            abort(404);
        }

        Gate::authorize('update', $regulation);

        return view('regulations.edit', [
            'regulation' => $regulation,
        ]);
    }

    /**
     * Update regulation
     */
    public function update(UpdateRegulationRequest $request, string $id): RedirectResponse
    {
        $regulation = $this->regulationService->getRegulation($id);
        
        if (!$regulation) {
            abort(404);
        }

        Gate::authorize('update', $regulation);

        $this->regulationService->updateRegulation($id, $request->validated());

        return redirect()
            ->route('regulations.show', $id)
            ->with('success', 'Normatividad actualizada correctamente.');
    }

    /**
     * Show requirements for a regulation
     */
    public function requirements(string $id): View
    {
        $regulation = $this->regulationService->getRegulation($id);
        
        if (!$regulation) {
            abort(404);
        }

        Gate::authorize('view', $regulation);

        $requirements = $this->regulationService->getRequirementsByRegulation($id);

        return view('regulations.requirements', [
            'regulation' => $regulation,
            'requirements' => $requirements,
        ]);
    }

    /**
     * Show create requirement form
     */
    public function createRequirement(string $regulationId): View
    {
        $regulation = $this->regulationService->getRegulation($regulationId);
        
        if (!$regulation) {
            abort(404);
        }

        Gate::authorize('create', \App\Models\Requirement::class);

        return view('regulations.requirements.create', [
            'regulation' => $regulation,
        ]);
    }

    /**
     * Store new requirement
     */
    public function storeRequirement(StoreRequirementRequest $request, string $regulationId): RedirectResponse
    {
        $regulation = $this->regulationService->getRegulation($regulationId);
        
        if (!$regulation) {
            abort(404);
        }

        Gate::authorize('create', \App\Models\Requirement::class);

        $requirement = $this->regulationService->createRequirement(
            $regulationId,
            $request->validated()
        );

        return redirect()
            ->route('regulations.requirements', $regulationId)
            ->with('success', 'Requisito creado correctamente.');
    }

    /**
     * Search regulations (AJAX)
     */
    public function search(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $regulations = $this->regulationService->searchRegulations($query);

        return response()->json($regulations->map(function ($regulation) {
            return [
                'id' => $regulation->id->toString(),
                'code' => $regulation->code->toString(),
                'name' => $regulation->name->toString(),
            ];
        }));
    }
}
