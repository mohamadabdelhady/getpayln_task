<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class reservations extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'expiration_date',
    ];

    public function product()
    {
        return $this->belongsTo(products::class, 'product_id');
    }

    public function order()
    {
        return $this->belongsTo(orders::class, 'hold_id');
    }
}
