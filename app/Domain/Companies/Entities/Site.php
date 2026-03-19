<?php

namespace App\Domain\Companies\Entities;

use App\Domain\Shared\ValueObjects\UUID;
use DateTimeImmutable;
use InvalidArgumentException;

class Site
{
    public function __construct(
        private readonly UUID $id,
        private int $companyId,
        private string $name,
        private string $code,
        private string $type = 'office',
        private ?string $address = null,
        private ?string $city = null,
        private ?string $state = null,
        private ?string $zipCode = null,
        private ?string $phone = null,
        private ?float $latitude = null,
        private ?float $longitude = null,
        private bool $isMain = false,
        private string $status = 'active',
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ) {}

    public function getId(): UUID
    {
        return $this->id;
    }

    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function deactivate(): void
    {
        $this->status = 'inactive';
        $this->updatedAt = new DateTimeImmutable();
    }

    public function activate(): void
    {
        $this->status = 'active';
        $this->updatedAt = new DateTimeImmutable();
    }

    public function setAsMain(): void
    {
        $this->isMain = true;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function updateLocation(float $latitude, float $longitude): void
    {
        if ($latitude < -90 || $latitude > 90) {
            throw new InvalidArgumentException('Invalid latitude');
        }
        if ($longitude < -180 || $longitude > 180) {
            throw new InvalidArgumentException('Invalid longitude');
        }
        
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function updateInfo(array $data): void
    {
        $allowedFields = ['name', 'address', 'city', 'state', 'zipCode', 'phone', 'type'];
        
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
            'company_id' => $this->companyId,
            'name' => $this->name,
            'code' => $this->code,
            'type' => $this->type,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zipCode,
            'phone' => $this->phone,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_main' => $this->isMain,
            'status' => $this->status,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
