<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\orders;

class ordersFactory extends Factory
{
    protected $model = orders::class;

    public function definition()
    {
        return [
            'hold_id' => null,
            'status' => 'pending',
        ];
    }
}
