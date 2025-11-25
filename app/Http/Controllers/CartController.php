<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Index - Menampilkan cart user
    public function index()
    {
        $cart = Cart::with(['items.product'])
            ->where('user_id', Auth::id())
            ->first();

        // Ambil 4 produk terlaris
        $bestSellingProducts = $this->getBestSellingProducts(4);

        return view('cart.index', compact('cart', 'bestSellingProducts'));
    }

    // Method untuk mendapatkan produk terlaris
    private function getBestSellingProducts($limit = 4)
    {
        return Product::where('is_active', 1)
            ->where('stock', '>', 0)
            ->withSum([
                'transactionItems' => function($query) {
                    $query->whereHas('transaction', function($q) {
                        $q->where('payment_status', 'PAID');
                    });
                }
            ], 'quantity')
            ->orderBy('transaction_items_sum_quantity', 'desc')
            ->take($limit)
            ->get();
    }

    // Store - Menambah item ke cart
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'sometimes|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1;

        // Cek stok
        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Insufficient stock!');
        }

        // Dapatkan atau buat cart untuk user
        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id()],
            ['total_amount' => 0]
        );

        // Cek apakah item sudah ada di cart
        $existingItem = $cart->items()
            ->where('product_id', $product->id)
            ->first();

        if ($existingItem) {
            // Update quantity jika item sudah ada
            $newQuantity = $existingItem->quantity + $quantity;

            if ($product->stock < $newQuantity) {
                return redirect()->back()->with('error', 'Cannot add more than available stock!');
            }

            $existingItem->quantity = $newQuantity;
            $existingItem->updateSubtotal();
        } else {
            // Tambah item baru
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
                'subtotal' => $product->price * $quantity
            ]);
        }

        // Update total cart
        $cart->updateTotal();

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    // Update - Update quantity item di cart
    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // Pastikan cart item milik user yang login
        if ($cartItem->cart->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action!');
        }

        // Cek stok
        if ($cartItem->product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Insufficient stock!');
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->updateSubtotal();

        // Update total cart
        $cartItem->cart->updateTotal();

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    // Destroy - Hapus item dari cart
    public function destroy(CartItem $cartItem)
    {
        // Pastikan cart item milik user yang login
        if ($cartItem->cart->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action!');
        }

        $cart = $cartItem->cart;
        $cartItem->delete();

        // Update total cart
        $cart->updateTotal();

        return redirect()->back()->with('success', 'Item removed from cart!');
    }

    // Get cart count untuk navbar
    public function getCartCount()
    {
        if (!Auth::check()) {
            return 0;
        }

        $cart = Cart::with('items')
            ->where('user_id', Auth::id())
            ->first();

        return $cart ? $cart->items->count() : 0;
    }
}
