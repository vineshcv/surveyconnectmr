<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Client;
use App\Models\ProjectUrl;
use App\Models\Country;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'projectID',
        'project_type',
        'specifications',
        'quota',
        'loi',
        'ir',
        'status',
        'client_id',
        'login_type_id',
        'enable_questions',
    ];

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'project_question');
    }
    public function urls(): HasMany
    {
        return $this->hasMany(ProjectUrl::class);
    }
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'project_countries', 'project_id', 'country_id');
    }

    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class, 'vendor_quotas')
                    ->withPivot(['quota_allot', 'quota_used'])
                    ->withTimestamps();
    }

    public function vendorQuotas(): HasMany
    {
        return $this->hasMany(VendorQuota::class);
    }

    public function vendorMappings(): HasMany
    {
        return $this->hasMany(VendorMapping::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }
}
