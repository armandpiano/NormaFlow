<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Companies\Entities\Site;
use App\Domain\Companies\Repositories\SiteRepositoryInterface;
use App\Models\Site as SiteModel;

class EloquentSiteRepository implements SiteRepositoryInterface
{
    public function findById(int $id): ?Site
    {
        $model = SiteModel::find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function findByCompany(int $companyId): array
    {
        return SiteModel::where('company_id', $companyId)
            ->orderBy('is_main', 'desc')
            ->orderBy('name')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function findMainSite(int $companyId): ?Site
    {
        $model = SiteModel::where('company_id', $companyId)
            ->where('is_main', true)
            ->first();
        return $model ? $this->toEntity($model) : null;
    }

    public function save(Site $site): Site
    {
        $model = SiteModel::updateOrCreate(
            ['id' => $site->getIdInt()],
            [
                'company_id' => $site->getCompanyId(),
                'name' => $site->getName(),
                'code' => $site->getCode(),
                'type' => $site->type,
                'address' => $site->address,
                'city' => $site->city,
                'state' => $site->state,
                'zip_code' => $site->zipCode,
                'phone' => $site->phone,
                'latitude' => $site->latitude,
                'longitude' => $site->longitude,
                'is_main' => $site->isMain,
                'status' => $site->status,
            ]
        );
        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        SiteModel::destroy($id);
    }

    private function toEntity(SiteModel $model): Site
    {
        return new Site(
            id: \App\Domain\Shared\ValueObjects\UUID::fromString((string) $model->id),
            companyId: $model->company_id,
            name: $model->name,
            code: $model->code,
            type: $model->type,
            address: $model->address,
            city: $model->city,
            state: $model->state,
            zipCode: $model->zip_code,
            phone: $model->phone,
            latitude: $model->latitude,
            longitude: $model->longitude,
            isMain: $model->is_main,
            status: $model->status,
            createdAt: $model->created_at,
            updatedAt: $model->updated_at
        );
    }
}
