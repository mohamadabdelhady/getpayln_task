<?php

namespace App\Http\Controllers;

use App\Models\orders;
use App\Models\reservations;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'hold_id' => 'required|exists:reservations,id',
        ]);

        $reservation=reservations::find($request->hold_id);
        if ($reservation->expiration_date < now()) {
            return response()->json(['message' => 'Hold has expired'], 400);
        }

        $order=orders::find( $request->hold_id);
        if ($order) {
            return response()->json(['message' => 'Order already exists for this hold'], 400);
        }

        $order = orders::create([
            'hold_id' => $request->hold_id,
            'status' => 'pending',
        ]);

        if (!$order) {
            return response()->json(['message' => 'Failed to create order'], 500);
        }

        return response()->json([
            'order_id' => $order->id,
            'hold_id' => $order->hold_id,
            'status' => $order->status,
        ], 201);
    }
}
