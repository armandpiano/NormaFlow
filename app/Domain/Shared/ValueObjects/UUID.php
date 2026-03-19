<?php

namespace App\Domain\Shared\ValueObjects;

use Ramsey\Uuid\Uuid as BaseUuid;
use InvalidArgumentException;

final class UUID
{
    private string $value;

    public function __construct(?string $value = null)
    {
        $this->value = $value ?? BaseUuid::uuid4()->toString();
        
        if (!$this->isValid($this->value)) {
            throw new InvalidArgumentException("Invalid UUID: {$this->value}");
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function isValid(string $value): bool
    {
        return BaseUuid::isValid($value);
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function generate(): self
    {
        return new self();
    }
}
