<?php

declare(strict_types=1);

namespace App\UI\WEB\Controllers;

use App\Application\Services\AuditService;
use App\UI\WEB\Requests\StoreAuditRequest;
use App\UI\WEB\Requests\UpdateAuditRequest;
use App\UI\WEB\Requests\CompleteAuditRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class AuditController extends Controller
{
    public function __construct(
        private readonly AuditService $auditService
    ) {}

    /**
     * Display list of audits
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', \App\Models\Audit::class);

        $user = Auth::user();
        $companyId = $user->companyId?->toString();

        if ($companyId) {
            $audits = $this->auditService->getAuditsByCompany($companyId);
        } else {
            $audits = collect();
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $audits = $this->auditService->getAuditsByStatus($request->status);
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $audits = $this->auditService->getAuditsByType($request->type);
        }

        return view('audits.index', [
            'audits' => $audits,
            'filters' => $request->only(['status', 'type']),
        ]);
    }

    /**
     * Show audit details
     */
    public function show(Request $request, string $id): View
    {
        $audit = $this->auditService->getAudit($id);
        
        if (!$audit) {
            abort(404);
        }

        Gate::authorize('view', $audit);

        $stats = [
            'findings_total' => count($audit->getFindings()),
            'findings_open' => collect($audit->getFindings())->filter(fn($f) => $f->status->value === 'open')->count(),
        ];

        return view('audits.show', [
            'audit' => $audit,
            'stats' => $stats,
        ]);
    }

    /**
     * Show create form
     */
    public function create(): View
    {
        Gate::authorize('create', \App\Models\Audit::class);

        return view('audits.create');
    }

    /**
     * Store new audit
     */
    public function store(StoreAuditRequest $request): RedirectResponse
    {
        Gate::authorize('create', \App\Models\Audit::class);

        $data = $request->validated();
        $data['created_by'] = Auth::id();

        $audit = $this->auditService->createAudit($data);

        return redirect()
            ->route('audits.show', $audit->id->toString())
            ->with('success', 'Auditoría creada correctamente.');
    }

    /**
     * Show edit form
     */
    public function edit(Request $request, string $id): View
    {
        $audit = $this->auditService->getAudit($id);
        
        if (!$audit) {
            abort(404);
        }

        Gate::authorize('update', $audit);

        return view('audits.edit', [
            'audit' => $audit,
        ]);
    }

    /**
     * Update audit
     */
    public function update(UpdateAuditRequest $request, string $id): RedirectResponse
    {
        $audit = $this->auditService->getAudit($id);
        
        if (!$audit) {
            abort(404);
        }

        Gate::authorize('update', $audit);

        $this->auditService->updateAudit($id, $request->validated());

        return redirect()
            ->route('audits.show', $id)
            ->with('success', 'Auditoría actualizada correctamente.');
    }

    /**
     * Start audit
     */
    public function start(string $id): RedirectResponse
    {
        $audit = $this->auditService->getAudit($id);
        
        if (!$audit) {
            abort(404);
        }

        Gate::authorize('start', $audit);

        $this->auditService->startAudit($id);

        return redirect()
            ->route('audits.show', $id)
            ->with('success', 'Auditoría iniciada.');
    }

    /**
     * Complete audit
     */
    public function complete(CompleteAuditRequest $request, string $id): RedirectResponse
    {
        $audit = $this->auditService->getAudit($id);
        
        if (!$audit) {
            abort(404);
        }

        Gate::authorize('complete', $audit);

        $this->auditService->completeAudit($id, $request->validated());

        return redirect()
            ->route('audits.show', $id)
            ->with('success', 'Auditoría completada.');
    }

    /**
     * Cancel audit
     */
    public function cancel(Request $request, string $id): RedirectResponse
    {
        $audit = $this->auditService->getAudit($id);
        
        if (!$audit) {
            abort(404);
        }

        Gate::authorize('cancel', $audit);

        $this->auditService->cancelAudit($id, $request->input('reason', 'Cancelado por administrador'));

        return redirect()
            ->route('audits.show', $id)
            ->with('success', 'Auditoría cancelada.');
    }

    /**
     * Add finding to audit
     */
    public function addFinding(Request $request, string $id): View
    {
        $audit = $this->auditService->getAudit($id);
        
        if (!$audit) {
            abort(404);
        }

        Gate::authorize('create', \App\Models\Finding::class);

        return view('audits.findings.create', [
            'audit' => $audit,
        ]);
    }
}
