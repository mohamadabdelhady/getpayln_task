<?php

namespace App\Http\Controllers;

use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationsController extends Controller
{
    public function hold(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        
        return DB::transaction(function () use ($request) {
            $product = products::lockForUpdate()->find($request->product_id);

            if ($product->available_quantity < $request->quantity) {
                return response()->json(['message' => 'Insufficient stock available'], 400);
            }

            $reservation = $product->reservations()->create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'expiration_date' => now()->addMinutes(2),
            ]);

            if (!$reservation) {
                return response()->json(['message' => 'Failed to create hold'], 500);
            }

            return response()->json([
                'hold_id' => $reservation->id,
                'product_id' => $reservation->product_id,
                'quantity' => $reservation->quantity,
                'expires_at' => $reservation->expiration_date,
            ], 201);
        });
    }
}
