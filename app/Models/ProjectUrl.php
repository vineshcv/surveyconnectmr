<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectUrl extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'type', 'url'];

    public function project() {
        return $this->belongsTo(Project::class);
    }
}
