<?php

namespace App\UI\API\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvidenceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getIdInt(),
            'requirement_id' => $this->getRequirementId(),
            'user_id' => $this->getUserId(),
            'site_id' => $this->getSiteId(),
            'title' => $this->getTitle(),
            'description' => $this->description,
            'file_path' => $this->filePath,
            'original_name' => $this->originalName,
            'mime_type' => $this->mimeType,
            'file_size' => $this->fileSize,
            'status' => $this->getStatus(),
            'valid_until' => $this->validUntil?->format('Y-m-d'),
            'days_until_expiration' => $this->daysUntilExpiration(),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
