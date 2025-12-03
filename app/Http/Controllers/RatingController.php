<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request, Transaction $transaction)
    {
        // Validasi apakah user adalah pemilik transaksi
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        // Validasi apakah transaksi sudah dibayar
        if ($transaction->payment_status !== 'PAID') {
            return back()->with('error', 'You can only rate products from paid transactions.');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Cek apakah produk ada di transaksi ini
        $isProductInTransaction = $transaction->items()
            ->where('product_id', $request->product_id)
            ->exists();

        if (!$isProductInTransaction) {
            return back()->with('error', 'Product not found in this transaction.');
        }

        // Cek apakah user sudah memberikan rating untuk produk ini
        $existingRating = Rating::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingRating) {
            // Update rating yang sudah ada
            $existingRating->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            
            return back()->with('success', 'Rating updated successfully!');
        }

        // Buat rating baru
        Rating::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Thank you for your rating!');
    }

    public function update(Request $request, Rating $rating)
    {
        // Pastikan user adalah pemilik rating
        if ($rating->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $rating->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Rating updated successfully!');
    }

    public function destroy(Rating $rating)
    {
        // Pastikan user adalah pemilik rating
        if ($rating->user_id !== auth()->id()) {
            abort(403);
        }

        $rating->delete();

        return back()->with('success', 'Rating deleted successfully!');
    }
}