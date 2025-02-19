<?php

namespace App\Models\Admin\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pincode extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['pincode', 'district_id', 'is_active'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function state()
    {
        return $this->hasOneThrough(State::class, District::class, 'id', 'id', 'district_id', 'state_id');
    }
}
