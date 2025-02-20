<?php

namespace App\Models;

use App\Models\Admin\Master\City;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}

