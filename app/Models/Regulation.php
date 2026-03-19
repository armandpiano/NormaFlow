<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Regulation extends Model
{
    protected $table = 'regulations';

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'authority',
        'scope',
        'effective_date',
        'review_date',
        'url',
        'metadata',
        'is_active',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'review_date' => 'date',
        'metadata' => 'array',
        'is_active' => 'boolean',
    ];

    public function requirements(): HasMany
    {
        return $this->hasMany(Requirement::class);
    }
}
