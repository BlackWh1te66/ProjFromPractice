<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'category_id' => 'required|integer|exists:device_categories,id',
            'description' => 'nullable|string',
            'price'       => 'nullable|numeric',
            'stock_status'=> 'nullable|string|max:50',
            'image'       => 'nullable|file|image|max:4096',
            'brand'       => 'nullable|string|max:100',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = '/storage/' . $path;
        }

        $product = Product::create($validated);

        // Для звичайної відправки форми робіть редирект:
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'product' => $product]);
        }
        return redirect()->back()->with('success', 'Товар успішно збережено!');
    }

    public function index()
    {
        return response()->json(Product::with('category')->get());
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'category_id' => 'required|integer|exists:device_categories,id',
            'description' => 'nullable|string',
            'price'       => 'nullable|numeric',
            'stock_status'=> 'nullable|string|max:50',
            'image'       => 'nullable|file|image|max:4096',
            'brand'       => 'nullable|string|max:100',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = '/storage/' . $path;
        }

        $product->update($validated);
        return response()->json(['success' => true, 'product' => $product]);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['success' => true]);
    }

    public function catalog()
    {
        return response()->json(\App\Models\Product::with('category')->get());
    }
}
