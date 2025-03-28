<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['purchase_order_id', 'product_id', 'qty', 'price', 'total_amt', 'taxable_amt', 'tax_percentage', 'tax_type', 'tax_amt', 'net_amt'];
    public function purchaseOrders(){
        return $this->belongsTo(PurchaseOrder::class);
    }
}
