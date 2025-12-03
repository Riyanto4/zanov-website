<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // Index - Menampilkan cart user
    public function index()
    {
        $cart = Cart::with(['items.product'])
            ->where('user_id', Auth::id())
            ->first();

        // Ambil 4 produk rekomendasi berdasarkan asosiasi
        $recommendedProducts = $this->getAssociationBasedProducts(4);

        return view('cart.index', compact('cart', 'recommendedProducts'));
    }

    // Method untuk mendapatkan produk rekomendasi berdasarkan algoritma asosiasi
    private function getAssociationBasedProducts($limit = 4, $minSupport = 0.1, $minConfidence = 0.3)
    {
        $currentCart = Cart::with('items.product')
            ->where('user_id', Auth::id())
            ->first();

        // Jika cart kosong, tampilkan produk terlaris biasa
        if (!$currentCart || $currentCart->items->isEmpty()) {
            return $this->getBestSellingProducts($limit);
        }

        // Ambil produk yang ada di cart saat ini
        $cartProductIds = $currentCart->items->pluck('product_id')->toArray();

        // Ambil semua transaksi yang sudah dibayar
        $transactions = Transaction::where('payment_status', 'PAID')
            ->with('items.product')
            ->get();

        if ($transactions->isEmpty()) {
            return $this->getBestSellingProducts($limit);
        }

        // Hitung support dan confidence untuk setiap produk
        $productAssociations = $this->calculateAssociations(
            $transactions, 
            $cartProductIds, 
            $minSupport, 
            $minConfidence
        );

        // Urutkan berdasarkan confidence tertinggi
        usort($productAssociations, function($a, $b) {
            if ($b['confidence'] == $a['confidence']) {
                return $b['support'] <=> $a['support'];
            }
            return $b['confidence'] <=> $a['confidence'];
        });

        // Ambil produk rekomendasi
        $recommendedProductIds = [];
        foreach ($productAssociations as $association) {
            if (count($recommendedProductIds) >= $limit) break;
            
            if (!in_array($association['product_id'], $cartProductIds)) {
                $recommendedProductIds[] = $association['product_id'];
            }
        }

        // Jika tidak cukup rekomendasi, tambahkan produk terlaris
        if (count($recommendedProductIds) < $limit) {
            $additionalProducts = $this->getBestSellingProducts(
                $limit - count($recommendedProductIds),
                $recommendedProductIds
            );
            
            $recommendedProductIds = array_merge(
                $recommendedProductIds,
                $additionalProducts->pluck('id')->toArray()
            );
        }

        // Ambil data produk
        return Product::whereIn('id', $recommendedProductIds)
            ->where('is_active', 1)
            ->where('stock', '>', 0)
            ->get();
    }

    // Hitung asosiasi menggunakan algoritma Apriori sederhana
    private function calculateAssociations($transactions, $cartProductIds, $minSupport, $minConfidence)
    {
        $totalTransactions = count($transactions);
        
        // Hitung support untuk setiap produk
        $productSupports = [];
        $productTransactions = [];
        
        foreach ($transactions as $transaction) {
            $transactionProductIds = $transaction->items->pluck('product_id')->toArray();
            
            foreach ($transactionProductIds as $productId) {
                if (!isset($productTransactions[$productId])) {
                    $productTransactions[$productId] = 0;
                }
                $productTransactions[$productId]++;
            }
        }
        
        // Konversi ke support
        foreach ($productTransactions as $productId => $count) {
            $productSupports[$productId] = $count / $totalTransactions;
        }
        
        // Hitung asosiasi untuk produk di cart
        $associations = [];
        
        foreach ($cartProductIds as $cartProductId) {
            foreach ($productSupports as $productId => $productSupport) {
                // Skip jika produk sama dengan di cart
                if (in_array($productId, $cartProductIds)) {
                    continue;
                }
                
                // Hitung support untuk pasangan produk
                $pairCount = 0;
                foreach ($transactions as $transaction) {
                    $transactionProductIds = $transaction->items->pluck('product_id')->toArray();
                    
                    if (in_array($cartProductId, $transactionProductIds) && 
                        in_array($productId, $transactionProductIds)) {
                        $pairCount++;
                    }
                }
                
                $pairSupport = $pairCount / $totalTransactions;
                
                // Hitung confidence
                if ($productSupports[$cartProductId] > 0) {
                    $confidence = $pairCount / ($productTransactions[$cartProductId]);
                    
                    // Filter berdasarkan minimum support dan confidence
                    if ($pairSupport >= $minSupport && $confidence >= $minConfidence) {
                        $associations[] = [
                            'cart_product_id' => $cartProductId,
                            'product_id' => $productId,
                            'support' => $pairSupport,
                            'confidence' => $confidence,
                            'lift' => $confidence / $productSupport
                        ];
                    }
                }
            }
        }
        
        return $associations;
    }

    // Fallback method untuk produk terlaris
    private function getBestSellingProducts($limit = 4, $excludeIds = [])
    {
        return Product::where('is_active', 1)
            ->where('stock', '>', 0)
            ->when(!empty($excludeIds), function($query) use ($excludeIds) {
                $query->whereNotIn('id', $excludeIds);
            })
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

    // Store - Menambah item ke cart (tetap sama)
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

    // Update - Update quantity item di cart (tetap sama)
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

    // Destroy - Hapus item dari cart (tetap sama)
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

    // Get cart count untuk navbar (tetap sama)
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