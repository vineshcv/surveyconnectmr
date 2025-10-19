<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'type', 'options', 'sub_questions' , 'status'];

    protected $casts = [
        'options' => 'array',
        'sub_questions' => 'array',
        'status' => 'boolean'
    ];

   
}
