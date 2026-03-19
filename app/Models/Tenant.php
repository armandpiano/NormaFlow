<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $table = 'tenants';

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'settings',
        'subscription_plan',
        'status',
        'subscription_starts_at',
        'subscription_ends_at',
    ];

    protected $casts = [
        'settings' => 'array',
        'subscription_starts_at' => 'date',
        'subscription_ends_at' => 'date',
    ];

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSubscriptionValid(): bool
    {
        if (!$this->isActive()) {
            return false;
        }
        
        if ($this->subscription_ends_at === null) {
            return true;
        }
        
        return $this->subscription_ends_at->gte(now());
    }
}
