<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'email',
        'mobile_number',
        'alternate_contact',
        'company_name',
        'address1',
        'address2',
        'state',
        'country',
        'pincode',
    ];
}
