<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorQuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'vendor_id',
        'quota_allot',
        'quota_used',
    ];

    protected $casts = [
        'quota_allot' => 'integer',
        'quota_used' => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get remaining quota
     */
    public function getRemainingQuotaAttribute(): int
    {
        return $this->quota_allot - $this->quota_used;
    }

    /**
     * Check if quota is full
     */
    public function getIsQuotaFullAttribute(): bool
    {
        return $this->quota_used >= $this->quota_allot;
    }
}