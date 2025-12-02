<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\products;

class productsFactory extends Factory
{
    protected $model = products::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(1, 1000),
            'stock_available' => $this->faker->numberBetween(0, 100),
        ];
    }
}
