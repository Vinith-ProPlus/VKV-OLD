<?php

namespace App\Models;

use App\Models\Admin\ManageProjects\ProjectStage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $messageData)
 */
class BlogDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'blog_id',
        'project_id',
        'project_stage_id',
        'remarks',
        'is_damage'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(ProjectStage::class);
    }

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }
}
