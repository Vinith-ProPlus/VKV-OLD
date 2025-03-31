<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static create(array $array)
 */
class Blog extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['user_id', 'stage_ids'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
