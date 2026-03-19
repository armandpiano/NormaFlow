<?php

namespace App\Domain\Companies\Entities;

use App\Domain\Shared\ValueObjects\UUID;
use DateTimeImmutable;
use InvalidArgumentException;

class Company
{
    public function __construct(
        private readonly UUID $id,
        private int $tenantId,
        private string $name,
        private string $rfc,
        private ?string $taxId = null,
        private ?string $industry = null,
        private ?string $address = null,
        private ?string $city = null,
        private ?string $state = null,
        private ?string $zipCode = null,
        private ?string $phone = null,
        private ?string $email = null,
        private ?string $website = null,
        private ?string $logoPath = null,
        private ?int $employeeCount = null,
        private string $status = 'active',
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ) {}

    public function getId(): UUID
    {
        return $this->id;
    }

    public function getIdInt(): int
    {
        return (int) $this->id->getValue();
    }

    public function getTenantId(): int
    {
        return $this->tenantId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRfc(): string
    {
        return $this->rfc;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function suspend(): void
    {
        if ($this->status === 'suspended') {
            throw new InvalidArgumentException('Company is already suspended');
        }
        $this->status = 'suspended';
        $this->updatedAt = new DateTimeImmutable();
    }

    public function activate(): void
    {
        if ($this->status === 'active') {
            throw new InvalidArgumentException('Company is already active');
        }
        $this->status = 'active';
        $this->updatedAt = new DateTimeImmutable();
    }

    public function updateProfile(array $data): void
    {
        $allowedFields = ['name', 'address', 'city', 'state', 'zipCode', 'phone', 'email', 'website', 'employeeCount'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $this->{$field} = $data[$field];
            }
        }
        
        $this->updatedAt = new DateTimeImmutable();
    }

    public function validateRfc(string $rfc): bool
    {
        // RFC regex for Mexico (12 chars for personas morales, 13 for personas fisicas)
        return preg_match('/^[A-Z]{3,4}[0-9]{6}[A-Z0-9]{3}$/', strtoupper($rfc)) === 1;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->getValue(),
            'tenant_id' => $this->tenantId,
            'name' => $this->name,
            'rfc' => $this->rfc,
            'tax_id' => $this->taxId,
            'industry' => $this->industry,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zipCode,
            'phone' => $this->phone,
            'email' => $this->email,
            'website' => $this->website,
            'logo_path' => $this->logoPath,
            'employee_count' => $this->employeeCount,
            'status' => $this->status,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
