<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'galleries'])->get();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        Log::info('Received request data:', $request->all());
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'main_photo_url' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $product = Product::create($validated);

        if ($request->has('gallery_photos')) {
            foreach ($request->gallery_photos as $photo) {
                $product->galleries()->create([
                    'photo_url' => $photo
                ]);
            }
        }

        return response()->json($product->load(['category', 'galleries']), 201);
    }

    public function show(Product $product)
    {
        return response()->json($product->load(['category', 'galleries']));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'main_photo_url' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update($validated);

        if ($request->has('gallery_photos')) {
            // Delete existing gallery
            $product->galleries()->delete();
            
            // Create new gallery
            foreach ($request->gallery_photos as $photo) {
                $product->galleries()->create([
                    'photo_url' => $photo
                ]);
            }
        }

        return response()->json($product->load(['category', 'galleries']));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
