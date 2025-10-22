<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'mapping_id',
        'project_id',
        'vendor_id',
        'study_url',
        'security_full_url',
        'success_url',
        'terminate_url',
        'over_quota_url',
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
     * Generate unique mapping ID
     */
    public static function generateMappingId(): string
    {
        return date('Ymd') . 'Map' . mt_rand(1000000000000, 9999999999999);
    }
}