<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\products;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HoldConcurrencyTest extends TestCase
{
     use RefreshDatabase;
    public function test_parallel_holds_cannot_oversell()
    {
        $product = products::factory()->create([
            'stock_available' => 5
        ]);

        
        $promises = [];

for ($i = 0; $i < 20; $i++) {
    $promises[] = function () use ($product) {
        return $this->postJson('/api/holds', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
    };
}

$results = array_map(fn($fn) => $fn(), $promises);


        $reserved = DB::table('reservations')
                      ->where('product_id', $product->id)
                      ->count();

        $this->assertEquals(5, $reserved);
    }
}
