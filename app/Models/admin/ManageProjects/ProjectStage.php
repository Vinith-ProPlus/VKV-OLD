<?php

namespace App\Models\admin\ManageProjects;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $all)
 * @method static findOrFail($id)
 */
class ProjectStage extends Model
{
    use SoftDeletes;

    protected $fillable = ['project_id', 'name', 'order_no'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
