<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorRegistration extends Model
{
    use HasFactory;

    protected $table = 'vendor_registrations'; // or 'vendors' if that's your table

    protected $fillable = [
        'vendor_name',
        'email',
        'contact_number',
        'alternative_contact',
        'company_name',
        'address_line_one',
        'address_line_two',
        'state',
        'country',
        'pincode',
        'status',
        'username',
        'password',
        'approved_by',
        'approved_at',
        'rejected_reason',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => '<span class="badge bg-warning">Pending</span>',
            self::STATUS_APPROVED => '<span class="badge bg-success">Approved</span>',
            self::STATUS_REJECTED => '<span class="badge bg-danger">Rejected</span>',
            default => '<span class="badge bg-secondary">Unknown</span>'
        };
    }
}
