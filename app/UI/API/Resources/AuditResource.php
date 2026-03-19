<?php

namespace App\UI\API\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getIdInt(),
            'site_id' => $this->getSiteId(),
            'user_id' => $this->getUserId(),
            'company_id' => $this->companyId,
            'name' => $this->name,
            'description' => $this->description,
            'audit_type' => $this->auditType,
            'planned_start_date' => $this->plannedStartDate->format('Y-m-d'),
            'planned_end_date' => $this->plannedEndDate?->format('Y-m-d'),
            'status' => $this->getStatus(),
            'planned_duration_days' => $this->getPlannedDurationDays(),
            'actual_duration_days' => $this->getActualDurationDays(),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
