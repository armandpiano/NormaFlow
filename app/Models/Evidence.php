<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evidence extends Model
{
    protected $table = 'evidences';

    protected $fillable = [
        'requirement_id',
        'user_id',
        'site_id',
        'title',
        'description',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'document_date',
        'valid_from',
        'valid_until',
        'status',
        'verified_by',
        'verified_at',
        'rejection_reason',
        'metadata',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'document_date' => 'datetime',
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
        'verified_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function requirement(): BelongsTo
    {
        return $this->belongsTo(Requirement::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function verifiedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
