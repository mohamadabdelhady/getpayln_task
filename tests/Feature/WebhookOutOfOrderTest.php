<?php

use App\Models\Order;
use App\Models\orders;
use App\Models\webhook;
use App\Models\WebhookLog;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WebhookOutOfOrderTest extends TestCase
{
    use RefreshDatabase;

    public function WebhookOutOfOrderTest()
    {
           $payload = [
        'order_id' => 999,
        'status' => 'success',
        'idempotency_key' => 'EARLY-001'
    ];

    // webhook arrives before order exists
    $this->postJson('/api/webhook', $payload)
        ->assertStatus(202);

    $this->assertTrue(webhook::where('idempotency_key', 'EARLY-001')->exists());

    // now create order
    $order = orders::factory()->create([
        'id' => 999,
        'status' => 'pending'
    ]);

    // resend webhook
    $this->postJson('/api/webhook', $payload)->assertOk();

    $order->refresh();

    $this->assertEquals('completed', $order->status);
}
}
