<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
