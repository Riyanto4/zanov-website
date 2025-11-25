<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\StockStatement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function showAll()
    {
        $transactions = Transaction::with(['items.product', 'user', 'verifier'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.transaction.show-all', compact('transactions'));
    }

    public function index()
    {
        $transactions = Transaction::with(['items.product'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('transaction.index', compact('transactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|in:CASH,TRANSFER,QRIS,COD',
            'notes' => 'nullable|string|max:1000',
            'proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Get current user's cart with items
        $cart = Cart::with('items.product')
            ->where('user_id', auth()->id())
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            // Generate unique reference number
            $referenceNo = 'TRX-' . Str::upper(Str::random(8)) . '-' . time();

            // Handle proof upload
            $proofPath = null;
            if ($request->hasFile('proof')) {
                $proofPath = $request->file('proof')->store('proofs', 'public');
            }

            // Create transaction
            $transaction = Transaction::create([
                'reference_no' => $referenceNo,
                'name' => $request->name,
                'address' => $request->address,
                'total_amount' => $cart->total_amount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'PENDING',
                'proof' => $proofPath ?? null,
                'notes' => $request->notes ?? null,
                'user_id' => auth()->id(),
            ]);

            // Create transaction items from cart items
            foreach ($cart->items as $cartItem) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'subtotal' => $cartItem->subtotal,
                ]);

                // Update product stock
                $product = Product::find($cartItem->product_id);
                if ($product) {
                    $product->decrement('stock', $cartItem->quantity);
                }

                StockStatement::create([
                    'code' => 'STMT-' . Str::upper(Str::random(6)) . '-' . time(),
                    'product_id' => $product->id,
                    'type' => 'OUT',
                    'quantity' => $cartItem->quantity,
                    'description' => 'Stock decreased due to transaction ' . $referenceNo,
                    'created_by' => auth()->id(),
                ]);
            }

            // Delete cart items and cart
            $cart->items()->delete();
            $cart->delete();

            DB::commit();

            return redirect()->route('transactions.show', $transaction->id)
                ->with('success', 'Order placed successfully! Your reference number: ' . $referenceNo);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create transaction: ' . $e->getMessage());
        }
    }

    public function show(Transaction $transaction)
    {
        // Ensure user can only see their own transactions
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $transaction->load('items.product');

        return view('transaction.show', compact('transaction'));
    }

    public function create()
    {
        $cart = Cart::with('items.product')
            ->where('user_id', auth()->id())
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('checkout', compact('cart'));
    }

    // New function to verify transaction
    public function verify(Request $request, Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            $transaction->update([
                'payment_status' => 'PAID',
                'verifier_id' => auth()->id(),
                'verified_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('transactions.all')
                ->with('success', 'Transaction verified successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to verify transaction: ' . $e->getMessage());
        }
    }

    // New function to cancel transaction
    public function cancel(Request $request, Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            $transaction->update([
                'payment_status' => 'CANCELED',
                'verifier_id' => auth()->id(),
                'verified_at' => now(),
            ]);

            // Restore product stock
            foreach ($transaction->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }

                StockStatement::create([
                    'code' => 'STMT-' . Str::upper(Str::random(6)) . '-' . time(),
                    'product_id' => $product->id,
                    'type' => 'IN',
                    'quantity' => $item->quantity,
                    'description' => 'Stock restored due to transaction cancellation ' . $transaction->reference_no,
                    'created_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return redirect()->route('transactions.all')->with('success', 'Transaction canceled successfully! Stock has been restored.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to cancel transaction: ' . $e->getMessage());
        }
    }
}
