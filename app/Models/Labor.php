<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $only)
 */
class Labor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['project_labor_date_id', 'name', 'mobile', 'salary', 'designation', 'paid_status'];

    /**
     * @return BelongsTo
     */
    public function projectLaborDate(): BelongsTo
    {
        return $this->belongsTo(ProjectLaborDate::class);
    }
}
