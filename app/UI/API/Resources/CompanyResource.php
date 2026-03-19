<?php

namespace App\UI\API\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getIdInt(),
            'tenant_id' => $this->getTenantId(),
            'name' => $this->getName(),
            'rfc' => $this->getRfc(),
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
