<?php

namespace App\Domain\Shared\ValueObjects;

use DateTimeImmutable;
use InvalidArgumentException;

final class DateRange
{
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;

    public function __construct(DateTimeImmutable $start, DateTimeImmutable $end)
    {
        if ($start > $end) {
            throw new InvalidArgumentException('Start date must be before or equal to end date');
        }
        
        $this->start = $start;
        $this->end = $end;
    }

    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }

    public function getEnd(): DateTimeImmutable
    {
        return $this->end;
    }

    public function contains(DateTimeImmutable $date): bool
    {
        return $date >= $this->start && $date <= $this->end;
    }

    public function overlaps(self $other): bool
    {
        return $this->start <= $other->end && $this->end >= $other->start;
    }

    public function getDays(): int
    {
        return (int) $this->start->diff($this->end)->days;
    }

    public function isExpired(): bool
    {
        return $this->end < new DateTimeImmutable();
    }

    public function daysUntilExpiration(): int
    {
        $now = new DateTimeImmutable();
        if ($this->end < $now) {
            return 0;
        }
        return (int) $now->diff($this->end)->days;
    }
}
