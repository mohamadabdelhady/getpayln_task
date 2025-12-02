<?php

namespace App\Http\Controllers;

use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function show($id)
    {
        // Cache unchanging columns (name, description, price)
        $cacheKey = "product_static_{$id}";
        $staticData = Cache::remember($cacheKey, 24 * 60, function () use ($id) {
            $product = products::find($id);

            if (!$product) {
                return null;
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
            ];
        });

        if (!$staticData) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Fetch changing columns dynamically
        $product = products::find($id, ['id', 'stock_available']);

        return response()->json(array_merge($staticData, [
            'available_quantity' => $product->available_quantity,
        ]));
    }
}
