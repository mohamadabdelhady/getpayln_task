<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'hold_id',
        'status',
    ];
    
    public function reservations()
    {
        return $this->hasMany(reservations::class, 'hold_id');
    }
}
