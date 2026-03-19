<?php

namespace App\UI\API\Controllers;

use App\Application\Services\EvidenceService;
use App\UI\API\Requests\StoreEvidenceRequest;
use App\UI\API\Requests\ApproveEvidenceRequest;
use App\UI\API\Requests\RejectEvidenceRequest;
use App\UI\API\Resources\EvidenceResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EvidenceController
{
    public function __construct(
        private readonly EvidenceService $evidenceService
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        // Filter by requirement if provided
        if ($request->has('requirement_id')) {
            $evidences = $this->evidenceService->getEvidencesByRequirement(
                $request->input('requirement_id')
            );
        } else {
            // Get all evidences for tenant
            $evidences = [];
        }
        
        return EvidenceResource::collection($evidences);
    }

    public function store(StoreEvidenceRequest $request): JsonResponse
    {
        $evidence = $this->evidenceService->uploadEvidence(
            $request->validated(),
            $request->file('file')
        );
        
        return response()->json([
            'message' => 'Evidence uploaded successfully',
            'data' => new EvidenceResource($evidence)
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $evidence = $this->evidenceService->getEvidence($id);
        
        if (!$evidence) {
            return response()->json(['message' => 'Evidence not found'], 404);
        }
        
        return response()->json([
            'data' => new EvidenceResource($evidence)
        ]);
    }

    public function approve(ApproveEvidenceRequest $request, int $id): JsonResponse
    {
        try {
            $evidence = $this->evidenceService->approveEvidence(
                $id,
                $request->user()->id
            );
            
            return response()->json([
                'message' => 'Evidence approved successfully',
                'data' => new EvidenceResource($evidence)
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function reject(RejectEvidenceRequest $request, int $id): JsonResponse
    {
        try {
            $evidence = $this->evidenceService->rejectEvidence(
                $id,
                $request->user()->id,
                $request->input('reason')
            );
            
            return response()->json([
                'message' => 'Evidence rejected successfully',
                'data' => new EvidenceResource($evidence)
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $this->evidenceService->deleteEvidence($id);
        return response()->json(['message' => 'Evidence deleted successfully']);
    }

    public function byRequirement(int $requirementId): AnonymousResourceCollection
    {
        $evidences = $this->evidenceService->getEvidencesByRequirement($requirementId);
        return EvidenceResource::collection($evidences);
    }
}
