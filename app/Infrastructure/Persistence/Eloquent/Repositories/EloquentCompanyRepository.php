<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Companies\Entities\Company;
use App\Domain\Companies\Repositories\CompanyRepositoryInterface;
use App\Models\Company as CompanyModel;

class EloquentCompanyRepository implements CompanyRepositoryInterface
{
    public function findById(int $id): ?Company
    {
        $model = CompanyModel::find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function findByTenant(int $tenantId): array
    {
        return CompanyModel::where('tenant_id', $tenantId)
            ->orderBy('name')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function findByRfc(string $rfc): ?Company
    {
        $model = CompanyModel::where('rfc', $rfc)->first();
        return $model ? $this->toEntity($model) : null;
    }

    public function save(Company $company): Company
    {
        $model = CompanyModel::updateOrCreate(
            ['id' => $company->getIdInt()],
            [
                'tenant_id' => $company->getTenantId(),
                'name' => $company->getName(),
                'rfc' => $company->getRfc(),
                'tax_id' => $company->taxId,
                'industry' => $company->industry,
                'address' => $company->address,
                'city' => $company->city,
                'state' => $company->state,
                'zip_code' => $company->zipCode,
                'phone' => $company->phone,
                'email' => $company->email,
                'website' => $company->website,
                'logo_path' => $company->logoPath,
                'employee_count' => $company->employeeCount,
                'status' => $company->status,
            ]
        );
        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        CompanyModel::destroy($id);
    }

    public function findActiveByTenant(int $tenantId): array
    {
        return CompanyModel::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    private function toEntity(CompanyModel $model): Company
    {
        return new Company(
            id: \App\Domain\Shared\ValueObjects\UUID::fromString((string) $model->id),
            tenantId: $model->tenant_id,
            name: $model->name,
            rfc: $model->rfc,
            taxId: $model->tax_id,
            industry: $model->industry,
            address: $model->address,
            city: $model->city,
            state: $model->state,
            zipCode: $model->zip_code,
            phone: $model->phone,
            email: $model->email,
            website: $model->website,
            logoPath: $model->logo_path,
            employeeCount: $model->employee_count,
            status: $model->status,
            createdAt: $model->created_at,
            updatedAt: $model->updated_at
        );
    }
}
