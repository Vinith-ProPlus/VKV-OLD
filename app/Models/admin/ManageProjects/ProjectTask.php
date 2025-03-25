<?php

namespace App\Models\admin\ManageProjects;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo as BelongsToAlias;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Yajra\DataTables\Html\Editor\Fields\BelongsTo;

/**
 * @method static findOrFail($id)
 * @method static create(array $all)
 */
class ProjectTask extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'stage_id',
        'name',
        'date',
        'description',
        'status',
    ];

    /**
     * @return BelongsToAlias
     */
    public function stage(): BelongsToAlias
    {
        return $this->BelongsTo(ProjectStage::class);
    }

    /**
     * @return BelongsToAlias
     */
    public function project(): BelongsToAlias
    {
        return $this->BelongsTo(Project::class);
    }
}

