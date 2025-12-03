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
use ArielMejiaDev\LarapexCharts\LarapexChart;

class TransactionController extends Controller
{
    protected $chart;
    
    // Tambahkan constructor jika belum ada
    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }
    
    public function showAll(Request $request)
    {
        // Ambil parameter filter dari request
        $status = $request->input('status');
        $paymentMethod = $request->input('payment_method');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $search = $request->input('search');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        // Query dasar dengan eager loading
        $query = Transaction::with(['items.product', 'user', 'verifier']);

        // Filter berdasarkan status
        if ($status && $status !== 'all') {
            $query->where('payment_status', $status);
        }

        // Filter berdasarkan metode pembayaran
        if ($paymentMethod && $paymentMethod !== 'all') {
            $query->where('payment_method', $paymentMethod);
        }

        // Filter berdasarkan tanggal
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Filter berdasarkan pencarian (reference_no atau nama customer)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('email', 'like', "%{$search}%");
                });
            });
        }

        // Sorting
        $validSortColumns = ['created_at', 'total_amount', 'payment_status', 'reference_no'];
        $sortBy = in_array($sortBy, $validSortColumns) ? $sortBy : 'created_at';
        $sortOrder = $sortOrder === 'asc' ? 'asc' : 'desc';
        
        $query->orderBy($sortBy, $sortOrder);

        // Paginate results
        $transactions = $query->paginate(10)->appends($request->except('page'));

        // Data untuk chart (tetap semua data)
        $statusData = Transaction::select('payment_status')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('payment_status')
            ->get();

        $statusLabels = $statusData->pluck('payment_status')->toArray();
        $statusCounts = $statusData->pluck('count')->toArray();

        // Buat chart
        $chart = app(LarapexChart::class)->donutChart()
            ->setTitle('Distribution of Transaction Status')
            ->setSubtitle('All transactions')
            ->addData($statusCounts)
            ->setLabels($statusLabels)
            ->setColors(['#22c55e', '#f97316', '#ef4444', '#6b7280', '#8b5cf6'])
            ->setHeight(250);

        // Get filter values for view
        $filters = [
            'status' => $status,
            'payment_method' => $paymentMethod,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'search' => $search,
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
        ];

        return view('admin.transaction.show-all', compact('transactions', 'chart', 'filters'));
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

        $transaction->load(['items.product', 'items.product.ratings' => function($query) {
            $query->where('user_id', auth()->id());
        }]);

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
