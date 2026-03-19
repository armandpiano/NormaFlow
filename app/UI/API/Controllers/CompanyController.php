<?php

namespace App\UI\API\Controllers;

use App\Application\Services\CompanyService;
use App\UI\API\Requests\StoreCompanyRequest;
use App\UI\API\Requests\UpdateCompanyRequest;
use App\UI\API\Resources\CompanyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompanyController
{
    public function __construct(
        private readonly CompanyService $companyService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        $companies = $this->companyService->listCompaniesByTenant(
            request()->header('X-Tenant-ID')
        );
        return CompanyResource::collection($companies);
    }

    public function store(StoreCompanyRequest $request): JsonResponse
    {
        $company = $this->companyService->createCompany($request->validated());
        return response()->json([
            'message' => 'Company created successfully',
            'data' => new CompanyResource($company)
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $company = $this->companyService->getCompany($id);
        
        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }
        
        return response()->json([
            'data' => new CompanyResource($company)
        ]);
    }

    public function update(UpdateCompanyRequest $request, int $id): JsonResponse
    {
        try {
            $company = $this->companyService->updateCompany($id, $request->validated());
            return response()->json([
                'message' => 'Company updated successfully',
                'data' => new CompanyResource($company)
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $this->companyService->deleteCompany($id);
        return response()->json(['message' => 'Company deleted successfully']);
    }

    public function suspend(int $id): JsonResponse
    {
        try {
            $company = $this->companyService->suspendCompany($id);
            return response()->json([
                'message' => 'Company suspended successfully',
                'data' => new CompanyResource($company)
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function activate(int $id): JsonResponse
    {
        try {
            $company = $this->companyService->activateCompany($id);
            return response()->json([
                'message' => 'Company activated successfully',
                'data' => new CompanyResource($company)
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
