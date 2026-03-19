<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Identity\Entities\User;
use App\Domain\Identity\Repositories\UserRepositoryInterface;
use App\Models\User as UserModel;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        $model = UserModel::find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function findByEmail(string $email, int $tenantId): ?User
    {
        $model = UserModel::where('email', $email)
            ->where('tenant_id', $tenantId)
            ->first();
        return $model ? $this->toEntity($model) : null;
    }

    public function findByTenant(int $tenantId): array
    {
        return UserModel::where('tenant_id', $tenantId)
            ->orderBy('name')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function findByCompany(int $companyId): array
    {
        return UserModel::where('company_id', $companyId)
            ->orderBy('name')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function findActiveByTenant(int $tenantId): array
    {
        return UserModel::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function save(User $user): User
    {
        $model = UserModel::updateOrCreate(
            ['id' => $user->getIdInt()],
            [
                'tenant_id' => $user->getTenantId(),
                'company_id' => $user->getCompanyId(),
                'site_id' => $user->getSiteId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => $user->password,
                'role' => $user->getRole(),
                'position' => $user->position,
                'department' => $user->department,
                'employee_id' => $user->employeeId,
                'phone' => $user->phone,
                'avatar' => $user->avatar,
                'is_active' => $user->isActive,
            ]
        );
        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        UserModel::destroy($id);
    }

    private function toEntity(UserModel $model): User
    {
        return new User(
            id: \App\Domain\Shared\ValueObjects\UUID::fromString((string) $model->id),
            tenantId: $model->tenant_id,
            companyId: $model->company_id,
            siteId: $model->site_id,
            name: $model->name,
            email: $model->email,
            password: $model->password,
            role: $model->role,
            position: $model->position,
            department: $model->department,
            employeeId: $model->employee_id,
            phone: $model->phone,
            avatar: $model->avatar,
            isActive: $model->is_active,
            emailVerifiedAt: $model->email_verified_at,
            lastLoginAt: $model->last_login_at,
            createdAt: $model->created_at,
            updatedAt: $model->updated_at
        );
    }
}
