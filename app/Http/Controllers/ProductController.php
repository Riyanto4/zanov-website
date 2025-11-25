<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.product.index', compact('products'));
    }

    public function create()
    {
        return view('admin.product.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:products|max:50',
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:50',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'gender' => 'required|in:MALE,FEMALE,UNISEX',
            'is_active' => 'sometimes|boolean'
        ]);

        // Handle file upload
        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->store('products', 'public');
            $validated['photo'] = $imagePath;
        }

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('admin.product.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products')->ignore($product->id)
            ],
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:50',
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'gender' => 'required|in:MALE,FEMALE,UNISEX',
            'is_active' => 'nullable'
        ]);

        // Handle file upload jika ada gambar baru
        if ($request->hasFile('photo')) {
            // Hapus gambar lama jika ada
            if ($product->photo && Storage::disk('public')->exists($product->photo)) {
                Storage::disk('public')->delete($product->photo);
            }

            $imagePath = $request->file('photo')->store('products', 'public');
            $validated['photo'] = $imagePath;
        } else {
            // Jika tidak ada gambar baru, tetap gunakan gambar lama
            $validated['photo'] = $product->photo;
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Hapus gambar dari storage
        if ($product->photo && Storage::disk('public')->exists($product->photo)) {
            Storage::disk('public')->delete($product->photo);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function showCatalogue()
    {
        $products = Product::where('is_active', true)
            ->latest()
            ->paginate(9);

        return view('catalogue', compact('products'));
    }
}
