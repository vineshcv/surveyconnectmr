<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_name',
        'vendor_id',
        'email',
        'contact_number',
        'completed_redirect_url',
        'terminated_redirect_url',
        'quote_full_redirect_url',
        'security_full_redirect_url'
    ];

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'vendor_quotas')
                    ->withPivot(['quota_allot', 'quota_used'])
                    ->withTimestamps();
    }

    public function quotas(): HasMany
    {
        return $this->hasMany(VendorQuota::class);
    }

    public function mappings(): HasMany
    {
        return $this->hasMany(VendorMapping::class);
    }
}
