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
    ];
}
