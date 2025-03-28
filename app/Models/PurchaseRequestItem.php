<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequestItem extends Model
{
    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'req_id');
    }
}
