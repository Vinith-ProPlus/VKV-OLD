<?php

namespace App\Models\Admin\ManageProjects;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sites extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['project_id', 'is_active'];
}
