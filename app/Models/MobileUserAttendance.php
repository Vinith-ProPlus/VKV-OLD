<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $id)
 */
class MobileUserAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'latitude',
        'longitude',
        'ip_address',
        'device_id',
        'device_name',
        'time',
    ];
}
