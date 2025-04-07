<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(string $string, string $string1, string $string2)
 */
class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'purchase_request_id', 'project_id', 'supervisor_id', 'order_id',
        'order_date', 'remarks', 'status'
    ];

    public function details(): HasMany
    {
        return $this->hasMany(PurchaseOrderDetail::class);
    }

    public function request()
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public static function generateOrderID(): string
    {
        $datePrefix = now()->format('Y-md'); // e.g., 2025-0330

        // Get the last ticket number for today
        $lastOrder = self::where('order_id', 'LIKE', "{$datePrefix}%")
            ->orderBy('order_id', 'desc')
            ->first();

        // Extract the last 4 digits and increment
        $nextNumber = $lastOrder ? ((int)substr($lastOrder->order_id, -4) + 1) : 1;

        // Format the next number with leading zeros
        return $datePrefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($ticket) {
            $ticket->order_id = self::generateOrderID();
            $ticket->order_date = Carbon::now();
            $ticket->status = 'Pending';
        });
    }
}
