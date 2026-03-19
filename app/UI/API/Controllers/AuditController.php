<?php

namespace App\UI\API\Controllers;

use App\Application\Services\AuditService;
use App\UI\API\Requests\StoreAuditRequest;
use App\UI\API\Requests\CompleteAuditRequest;
use App\UI\API\Resources\AuditResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuditController
{
    public function __construct(
        private readonly AuditService $auditService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        if ($request->has('site_id')) {
            $audits = $this->auditService->getAuditsBySite($request->input('site_id'));
        } elseif ($request->input('status') === 'planificada') {
            $audits = $this->auditService->getPlannedAudits();
        } elseif ($request->input('status') === 'en_proceso') {
            $audits = $this->auditService->getAuditsInProgress();
        } else {
            $audits = [];
        }
        
        return AuditResource::collection($audits);
    }

    public function store(StoreAuditRequest $request): JsonResponse
    {
        $audit = $this->auditService->createAudit($request->validated());
        
        return response()->json([
            'message' => 'Audit created successfully',
            'data' => new AuditResource($audit)
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $audit = $this->auditService->getAudit($id);
        
        if (!$audit) {
            return response()->json(['message' => 'Audit not found'], 404);
        }
        
        return response()->json([
            'data' => new AuditResource($audit)
        ]);
    }

    public function start(int $id): JsonResponse
    {
        try {
            $audit = $this->auditService->startAudit($id);
            return response()->json([
                'message' => 'Audit started successfully',
                'data' => new AuditResource($audit)
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function complete(CompleteAuditRequest $request, int $id): JsonResponse
    {
        try {
            $audit = $this->auditService->completeAudit($id, $request->validated());
            return response()->json([
                'message' => 'Audit completed successfully',
                'data' => new AuditResource($audit)
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function cancel(int $id): JsonResponse
    {
        try {
            $audit = $this->auditService->cancelAudit($id);
            return response()->json([
                'message' => 'Audit cancelled successfully',
                'data' => new AuditResource($audit)
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $this->auditService->deleteAudit($id);
        return response()->json(['message' => 'Audit deleted successfully']);
    }
}
