<?php

namespace App\Models;

use App\Models\Admin\Master\City;
use App\Models\Admin\Master\District;
use App\Models\Admin\Master\Pincode;
use App\Models\Admin\Master\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static whereLoginType(string $string)
 */
class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'dob',
        'mobile',
        'address',
        'state_id',
        'city_id',
        'pincode_id',
        'district_id',
        'login_type',
        'password',
        'active_status'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function pincode()
    {
        return $this->belongsTo(Pincode::class);
    }
}
