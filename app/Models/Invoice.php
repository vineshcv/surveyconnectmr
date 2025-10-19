<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Partner;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'partner_id',
        'total_projects',
        'total_amount',
        'include_gst',
        'gst',
        'status',
    ];

    public function partner()
    {
        return $this->belongsTo(Vendor::class, 'partner_id');
    }

}
