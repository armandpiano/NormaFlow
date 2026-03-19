<?php

declare(strict_types=1);

namespace App\UI\WEB\Controllers;

use App\Application\Services\EvidenceService;
use App\UI\WEB\Requests\StoreEvidenceRequest;
use App\UI\WEB\Requests\ApproveEvidenceRequest;
use App\UI\WEB\Requests\RejectEvidenceRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EvidenceController extends Controller
{
    public function __construct(
        private readonly EvidenceService $evidenceService
    ) {}

    /**
     * Display list of evidence
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', \App\Models\Evidence::class);

        $user = Auth::user();
        $companyId = $user->companyId?->toString();

        if ($companyId) {
            $evidence = $this->evidenceService->getEvidenceByCompany($companyId);
        } else {
            $evidence = collect();
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            switch ($request->status) {
                case 'pending':
                    $evidence = $this->evidenceService->getPendingEvidence();
                    break;
                case 'approved':
                    $evidence = $this->evidenceService->getApprovedEvidence();
                    break;
                case 'rejected':
                    $evidence = $this->evidenceService->getRejectedEvidence();
                    break;
            }
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $evidence = $this->evidenceService->getEvidenceByType($request->type);
        }

        return view('evidence.index', [
            'evidence' => $evidence,
            'filters' => $request->only(['status', 'type']),
        ]);
    }

    /**
     * Show evidence details
     */
    public function show(Request $request, string $id): View
    {
        $evidence = $this->evidenceService->getEvidence($id);
        
        if (!$evidence) {
            abort(404);
        }

        Gate::authorize('view', $evidence);

        return view('evidence.show', [
            'evidence' => $evidence,
        ]);
    }

    /**
     * Show upload form
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', \App\Models\Evidence::class);

        $requirementId = $request->get('requirement_id');

        return view('evidence.create', [
            'requirementId' => $requirementId,
        ]);
    }

    /**
     * Upload evidence
     */
    public function store(StoreEvidenceRequest $request): RedirectResponse
    {
        Gate::authorize('create', \App\Models\Evidence::class);

        $user = Auth::user();
        $data = $request->validated();
        $data['uploaded_by'] = $user->id->toString();
        $data['company_id'] = $user->companyId?->toString();
        $data['site_id'] = $user->siteId?->toString();

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('evidence/' . date('Y/m'), 'public');
            
            $data['file_url'] = Storage::url($path);
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
            $data['mime_type'] = $file->getMimeType();
        }

        $evidence = $this->evidenceService->uploadEvidence($data);

        return redirect()
            ->route('evidence.show', $evidence->id->toString())
            ->with('success', 'Evidencia subida correctamente.');
    }

    /**
     * Download evidence file
     */
    public function download(string $id): RedirectResponse
    {
        $evidence = $this->evidenceService->getEvidence($id);
        
        if (!$evidence) {
            abort(404);
        }

        Gate::authorize('view', $evidence);

        if (!$evidence->fileUrl) {
            abort(404);
        }

        return redirect($evidence->fileUrl);
    }

    /**
     * Approve evidence
     */
    public function approve(ApproveEvidenceRequest $request, string $id): RedirectResponse
    {
        $evidence = $this->evidenceService->getEvidence($id);
        
        if (!$evidence) {
            abort(404);
        }

        Gate::authorize('approve', $evidence);

        $this->evidenceService->approveEvidence(
            $id,
            Auth::id(),
            $request->input('notes')
        );

        return redirect()
            ->route('evidence.show', $id)
            ->with('success', 'Evidencia aprobada.');
    }

    /**
     * Reject evidence
     */
    public function reject(RejectEvidenceRequest $request, string $id): RedirectResponse
    {
        $evidence = $this->evidenceService->getEvidence($id);
        
        if (!$evidence) {
            abort(404);
        }

        Gate::authorize('reject', $evidence);

        $this->evidenceService->rejectEvidence(
            $id,
            Auth::id(),
            $request->input('reason')
        );

        return redirect()
            ->route('evidence.show', $id)
            ->with('success', 'Evidencia rechazada.');
    }

    /**
     * Request revision
     */
    public function requestRevision(Request $request, string $id): RedirectResponse
    {
        $evidence = $this->evidenceService->getEvidence($id);
        
        if (!$evidence) {
            abort(404);
        }

        Gate::authorize('requestRevision', $evidence);

        $request->validate([
            'feedback' => 'required|string|max:1000',
        ]);

        $this->evidenceService->requestRevision(
            $id,
            Auth::id(),
            $request->input('feedback')
        );

        return redirect()
            ->route('evidence.show', $id)
            ->with('success', 'Revisión solicitada.');
    }

    /**
     * Delete evidence
     */
    public function destroy(string $id): RedirectResponse
    {
        $evidence = $this->evidenceService->getEvidence($id);
        
        if (!$evidence) {
            abort(404);
        }

        Gate::authorize('delete', $evidence);

        $this->evidenceService->deleteEvidence($id);

        return redirect()
            ->route('evidence.index')
            ->with('success', 'Evidencia eliminada.');
    }

    /**
     * Get pending evidence for verification (AJAX)
     */
    public function pending(): \Illuminate\Http\JsonResponse
    {
        $pending = $this->evidenceService->getPendingEvidence();

        return response()->json($pending->map(function ($evidence) {
            return [
                'id' => $evidence->id->toString(),
                'title' => $evidence->title->toString(),
                'type' => $evidence->type->value,
                'uploaded_by' => $evidence->uploadedBy,
                'uploaded_at' => $evidence->createdAt->format('d/m/Y H:i'),
            ];
        }));
    }
}
