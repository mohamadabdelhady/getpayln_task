<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_available',
    ];

    public function reservations()
    {
        return $this->hasMany(reservations::class, 'product_id');
    }

    public function getReservedQuantityAttribute()
    {
        return $this->reservations()->where('expiration_date', '>', now())->sum('quantity');
    }

    public function getAvailableQuantityAttribute()
    {
        return $this->stock_available - $this->reserved_quantity;
    }
}
