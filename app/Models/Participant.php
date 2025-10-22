<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_id',
        'project_id',
        'vendor_id',
        'uid',
        'status',
        'loi',
        'start_loi',
        'end_loi',
        'participant_ip',
        'end_ip',
    ];

    protected $casts = [
        'loi' => 'decimal:2',
        'start_loi' => 'datetime',
        'end_loi' => 'datetime',
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
     * Get status name
     */
    public function getStatusNameAttribute(): string
    {
        return match($this->status) {
            1 => 'Complete',
            2 => 'Terminate', 
            3 => 'Quota Full',
            4 => 'Security Full',
            5 => 'LOI Fail',
            6 => 'IR Count',
            7 => 'IP Fail',
            8 => 'URL Error',
            9 => 'Unknown',
            10 => 'Already Participated',
            default => 'Unknown'
        };
    }
}