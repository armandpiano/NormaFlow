<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requirement extends Model
{
    protected $table = 'requirements';

    protected $fillable = [
        'regulation_id',
        'code',
        'description',
        'obligation_type',
        'frequency',
        'evidence_type',
        'expiration_days',
        'criteria',
        'is_active',
    ];

    protected $casts = [
        'criteria' => 'array',
        'is_active' => 'boolean',
        'expiration_days' => 'integer',
    ];

    public function regulation(): BelongsTo
    {
        return $this->belongsTo(Regulation::class);
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(Evidence::class);
    }
}
