<?php

namespace App\Domain\Identity\Entities;

use App\Domain\Shared\ValueObjects\UUID;
use DateTimeImmutable;
use Illuminate\Support\Facades\Hash;

class User
{
    public function __construct(
        private readonly UUID $id,
        private int $tenantId,
        private ?int $companyId,
        private ?int $siteId,
        private string $name,
        private string $email,
        private string $password,
        private string $role,
        private ?string $position = null,
        private ?string $department = null,
        private ?string $employeeId = null,
        private ?string $phone = null,
        private ?string $avatar = null,
        private bool $isActive = true,
        private ?DateTimeImmutable $emailVerifiedAt = null,
        private ?DateTimeImmutable $lastLoginAt = null,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ) {}

    public function getId(): UUID
    {
        return $this->id;
    }

    public function getTenantId(): int
    {
        return $this->tenantId;
    }

    public function getCompanyId(): ?int
    {
        return $this->companyId;
    }

    public function getSiteId(): ?int
    {
        return $this->siteId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isCompanyAdmin(): bool
    {
        return $this->role === 'company_admin';
    }

    public function isSiteManager(): bool
    {
        return $this->role === 'site_manager';
    }

    public function isAuditor(): bool
    {
        return $this->role === 'auditor';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    public function canManageCompanies(): bool
    {
        return in_array($this->role, ['super_admin', 'company_admin']);
    }

    public function canManageUsers(): bool
    {
        return in_array($this->role, ['super_admin', 'company_admin', 'site_manager']);
    }

    public function canUploadEvidence(): bool
    {
        return !in_array($this->role, ['super_admin']);
    }

    public function canConductAudits(): bool
    {
        return in_array($this->role, ['super_admin', 'auditor', 'company_admin']);
    }

    public function canViewReports(): bool
    {
        return !in_array($this->role, ['super_admin']);
    }

    public function canAccessSite(int $siteId): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        
        if ($this->siteId !== null) {
            return $this->siteId === $siteId;
        }
        
        return true; // Company-level access
    }

    public function verifyPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }

    public function recordLogin(): void
    {
        $this->lastLoginAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function deactivate(): void
    {
        $this->isActive = false;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function activate(): void
    {
        $this->isActive = true;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function updateProfile(array $data): void
    {
        $allowedFields = ['name', 'position', 'department', 'phone', 'avatar'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $this->{$field} = $data[$field];
            }
        }
        
        $this->updatedAt = new DateTimeImmutable();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->getValue(),
            'tenant_id' => $this->tenantId,
            'company_id' => $this->companyId,
            'site_id' => $this->siteId,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'position' => $this->position,
            'department' => $this->department,
            'employee_id' => $this->employeeId,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'is_active' => $this->isActive,
            'email_verified_at' => $this->emailVerifiedAt?->format('Y-m-d H:i:s'),
            'last_login_at' => $this->lastLoginAt?->format('Y-m-d H:i:s'),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
