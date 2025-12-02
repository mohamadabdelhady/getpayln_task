<?php

use App\Models\orders;
use App\Models\products;
use App\Models\webhook;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WebhookIdempotencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_webhook_is_idempotent()
    {
        $product = products::factory()->create(['stock_available' => 10]);

        $holdResp = $this->postJson('/api/holds', [
            'product_id' => $product->id,
            'quantity' => 1,
        ])->json();

        $order = orders::factory()->create([
            'hold_id' => $holdResp['hold_id'],
            'status' => 'pending'
        ]);

        $payload = [
            'order_id' => $order->id,
            'status' => 'success',
            'idempotency_key' => 'KEY-123'
        ];

        // first webhook
        $this->postJson('/api/webhook', $payload)->assertOk();

        // repeated webhook with same key
        $this->postJson('/api/webhook', $payload)->assertOk();

        $order->refresh();

        $this->assertEquals('completed', $order->status);

        $this->assertEquals(1, webhook::where('idempotency_key', 'KEY-123')->count());
    }
}
