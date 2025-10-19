<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectCountry extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'country_id'];

    public function project() {
        return $this->belongsTo(Project::class);
    }
}
