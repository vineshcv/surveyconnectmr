<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'project_type', 'specifications', 'quota', 'loi', 'ir',
        'status', 'client_id', 'login_type_id', 'enable_questions'
    ];

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'project_countries', 'project_id', 'country_id');
    }

    public function urls() {
        return $this->hasMany(ProjectUrl::class);
    }

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function questions() {
        return $this->belongsToMany(Question::class);
    }
}
