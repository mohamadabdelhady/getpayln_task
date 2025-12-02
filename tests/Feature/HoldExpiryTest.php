<?php

use App\Models\Product;
use App\Models\products;
use App\Models\Reservation;
use App\Models\reservations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HoldExpiryTest extends TestCase
{
     use RefreshDatabase;
    public function test_expired_holds_restore_availability()
    {
        $product = products::factory()->create([
            'stock_available' => 10
        ]);

        $this->postJson('/api/holds', [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);


        $product->refresh();

        $this->assertEquals(7, $product->available_quantity);
    }
}
