<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Build query
        $query = Product::query();
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Gender filter
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        
        // Status filter
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_active', 1);
            } elseif ($request->status == 'inactive') {
                $query->where('is_active', 0);
            }
        }
        
        // Stock filter
        if ($request->filled('stock')) {
            switch ($request->stock) {
                case 'low':
                    $query->where('stock', '<=', 5)->where('stock', '>', 0);
                    break;
                case 'out':
                    $query->where('stock', 0);
                    break;
                case 'available':
                    $query->where('stock', '>', 0);
                    break;
            }
        }
        
        // Get products with low stock first, then others
        $lowStockProducts = (clone $query)->where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->get();
        
        $normalStockProducts = (clone $query)->where('stock', '>', 5)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Merge collections
        $products = $lowStockProducts->merge($normalStockProducts);
        
        // Paginate manually for merged collection
        $page = $request->get('page', 1);
        $perPage = 15;
        $paginatedProducts = new \Illuminate\Pagination\LengthAwarePaginator(
            $products->forPage($page, $perPage),
            $products->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        // Get low stock count for warning
        $lowStockCount = Product::where('stock', '<=', 5)->count();
        
        return view('admin.product.index', [
            'products' => $paginatedProducts,
            'lowStockCount' => $lowStockCount
        ]);
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

    public function showCatalogue(Request $request)
    {
        $query = Product::where('is_active', true)
            ->with(['ratings']) // Load ratings relationship
            ->withCount('ratings'); // Count total ratings

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Gender filter
        if ($request->filled('gender') && $request->gender !== 'all') {
            $query->where('gender', $request->gender);
        }

        $products = $query->latest()->paginate(9)->withQueryString();

        return view('catalogue', compact('products'));
    }
}