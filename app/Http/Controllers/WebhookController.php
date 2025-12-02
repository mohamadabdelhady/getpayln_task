<?php

namespace App\Http\Controllers;

use App\Models\orders;
use App\Models\webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
       $key = $request->idempotency_key;

       return DB::transaction(function () use ($request, $key) {

        if (webhook::where('idempotency_key', $key)->exists()) {
            return response()->json(['status' => 'duplicate'], 200);
        }
         webhook::create([
            'idempotency_key' => $key,
            'payload' => json_encode($request->all()),
            'order_id' => $request->order_id,
            'status' => $request->status,
        ]);

        $order = orders::lockForUpdate()->find($request->order_id);

        if (! $order) {
            return response()->json(['status' => 'order_not_found'], 202);
        }

        if ($request->status === 'success') {
            $order->status = 'completed';
            $order->save();
        }

        if ($request->status === 'failed') {
            $order->status = 'failed';
            $order->save();
        }

        return response()->json(['status' => 'processed']);
    });
    }
}
