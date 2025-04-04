<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'product_category_id',
        'product_id',
        'quantity',
        'rate',
        'gst_applicable',
        'gst_percentage',
        'gst_value',
        'total_amount',
        'total_amount_with_gst',
        'status',
        'remarks',
        'image_path',
        'delivery_date',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
