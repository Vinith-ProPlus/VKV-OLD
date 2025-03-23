<?php

namespace App\Models;

use App\Models\admin\ManageProjects\ProjectStage;
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
}

