<?php

namespace App\Models;

use App\Models\Admin\ManageProjects\ProjectStage;
use App\Models\Admin\ManageProjects\ProjectTask;
use App\Models\Admin\ManageProjects\Site;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static findOrFail($id)
 * @method static create(array $all)
 */
class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_id',
        'project_id',
        'name',
        'location',
        'type',
        'units',
        'target_customers',
        'range',
        'is_active'
    ];

    public function stages(): HasMany
    {
        return $this->HasMany(ProjectStage::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class);
    }
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}

