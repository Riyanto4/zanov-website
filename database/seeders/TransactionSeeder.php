<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use App\Models\StockStatement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada user untuk dijadikan sebagai pembeli
        $users = User::all();
        if ($users->isEmpty()) {
            // Buat user dummy jika belum ada
            $user = User::factory()->create();
            $users = collect([$user]);
        }

        // Ambil semua produk yang aktif dengan stock > 0
        $products = Product::where('is_active', 1)->where('stock', '>', 0)->get();
        
        if ($products->isEmpty()) {
            $this->command->warn('Tidak ada produk aktif dengan stok tersedia. Silakan jalankan ProductSeeder terlebih dahulu.');
            return;
        }

        // Daftar nama dan alamat dummy
        $dummyNames = [
            'John Doe', 'Jane Smith', 'Robert Johnson', 'Emily Davis', 'Michael Wilson',
            'Sarah Brown', 'David Miller', 'Lisa Taylor', 'James Anderson', 'Maria Thomas',
            'Daniel Martinez', 'Jennifer Garcia', 'Christopher Robinson', 'Patricia Clark',
            'Matthew Rodriguez', 'Linda Lewis', 'Anthony Lee', 'Susan Walker', 'Mark Hall',
            'Karen Allen'
        ];

        $dummyAddresses = [
            'Jl. Sudirman No. 123, Jakarta',
            'Jl. Thamrin No. 45, Jakarta Pusat',
            'Jl. Gatot Subroto No. 67, Jakarta Selatan',
            'Jl. MH Thamrin No. 89, Jakarta Pusat',
            'Jl. Rasuna Said No. 12, Kuningan',
            'Jl. Asia Afrika No. 34, Bandung',
            'Jl. Diponegoro No. 56, Semarang',
            'Jl. Malioboro No. 78, Yogyakarta',
            'Jl. Raya Kuta No. 90, Bali',
            'Jl. Ahmad Yani No. 11, Surabaya'
        ];

        // Generate 50 transaksi
        for ($i = 0; $i < 50; $i++) {
            $paymentMethod = ['CASH', 'TRANSFER', 'QRIS'][rand(0, 2)];
            
            // Probabilitas status pembayaran
            $statusRand = rand(1, 100);
            if ($statusRand <= 70) {
                $paymentStatus = 'PAID'; // 70% PAID
            } elseif ($statusRand <= 90) {
                $paymentStatus = 'PENDING'; // 20% PENDING
            } else {
                $paymentStatus = ['CANCELED', 'REFUNDED'][rand(0, 1)]; // 10% CANCELED/REFUNDED
            }

            // Pilih user random sebagai pembeli
            $user = $users->random();
            
            // Pilih user random sebagai verifier (untuk transaksi yang sudah PAID)
            $verifier = null;
            $verifiedAt = null;
            if ($paymentStatus === 'PAID' && rand(1, 100) <= 80) { // 80% transaksi PAID diverifikasi
                $verifier = $users->where('id', '!=', $user->id)->random();
                $verifiedAt = now()->subDays(rand(1, 30));
            }

            // Pilih produk dengan stock yang cukup
            $availableProducts = $products->where('stock', '>', 0);
            if ($availableProducts->isEmpty()) {
                $this->command->warn('Stok produk habis, transaksi berhenti di ' . $i . ' transaksi.');
                break;
            }

            // Buat transaksi
            $transaction = Transaction::create([
                'reference_no' => 'TRX-' . Str::random(10) . '-' . time(),
                'name' => $dummyNames[array_rand($dummyNames)],
                'address' => $dummyAddresses[array_rand($dummyAddresses)],
                'total_amount' => 0, // Akan diupdate setelah membuat item
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'notes' => rand(0, 1) ? 'Catatan untuk transaksi ini' : null,
                'proof' => $paymentMethod !== 'CASH' && $paymentStatus === 'PAID' ? 'proof_' . Str::random(10) . '.jpg' : null,
                'user_id' => $user->id,
                'verifier_id' => $verifier?->id,
                'verified_at' => $verifiedAt,
                'created_at' => now()->subDays(rand(0, 60)), // Transaksi dibuat dalam 60 hari terakhir
            ]);

            // Tambahkan 1-5 item transaksi
            $totalAmount = 0;
            $itemCount = rand(1, min(5, $availableProducts->count()));
            $selectedProducts = $availableProducts->random($itemCount);

            foreach ($selectedProducts as $product) {
                // Quantity maksimal tidak boleh melebihi stok yang ada
                $maxQuantity = min(5, $product->stock);
                $quantity = rand(1, $maxQuantity);
                $price = $product->price;
                $subtotal = $quantity * $price;

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                ]);

                $totalAmount += $subtotal;

                // Kurangi stok produk untuk transaksi yang PAID
                if ($paymentStatus === 'PAID') {
                    // Kurangi stok produk
                    $product->decrement('stock', $quantity);
                    
                    // Catat stock statement untuk OUT (penjualan)
                    StockStatement::create([
                        'code' => 'OUT-' . Str::random(8) . '-' . time(),
                        'product_id' => $product->id,
                        'type' => 'OUT',
                        'quantity' => $quantity,
                        'description' => 'Penjualan melalui transaksi ' . $transaction->reference_no,
                        'created_by' => $verifier?->id ?? $user->id,
                        'created_at' => $transaction->created_at,
                        'updated_at' => $transaction->created_at,
                    ]);
                    
                    // Refresh data produk untuk transaksi berikutnya
                    $product->refresh();
                    if ($product->stock <= 0) {
                        $products = $products->filter(function ($p) use ($product) {
                            return $p->id !== $product->id;
                        });
                    }
                }
                // Untuk transaksi CANCELED/REFUNDED yang sebelumnya sudah PAID
                // (simulasi pengembalian barang)
                elseif (in_array($paymentStatus, ['CANCELED', 'REFUNDED']) && rand(1, 100) <= 30) {
                    // 30% kemungkinan ada pengembalian stok
                    $returnQuantity = rand(1, $quantity);
                    
                    // Tambah stok produk (pengembalian)
                    $product->increment('stock', $returnQuantity);
                    
                    // Catat stock statement untuk IN (pengembalian)
                    StockStatement::create([
                        'code' => 'IN-' . Str::random(8) . '-' . time(),
                        'product_id' => $product->id,
                        'type' => 'IN',
                        'quantity' => $returnQuantity,
                        'description' => 'Pengembalian barang dari transaksi ' . $transaction->reference_no . ' (status: ' . $paymentStatus . ')',
                        'created_by' => $user->id,
                        'created_at' => $transaction->created_at->addDays(rand(1, 7)),
                        'updated_at' => $transaction->created_at->addDays(rand(1, 7)),
                    ]);
                }
            }

            // Update total amount transaksi
            $transaction->update(['total_amount' => $totalAmount]);
        }

        $this->command->info('Transaksi berhasil dibuat dengan pengecekan stok dan pencatatan stock statements.');
    }
}