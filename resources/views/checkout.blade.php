@extends('layouts.guest.main')

@section('content')
<section class="py-20 px-4 sm:px-6 lg:px-8 bg-secondary min-h-screen">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-4 uppercase tracking-tight">Checkout</h1>
            <p class="text-gray-300">Selesaikan pembelian Anda</p>
        </div>

        @if($cart && $cart->items->count() > 0)
            <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column - Customer Info & Payment -->
                    <div class="space-y-6">
                        <!-- Customer Information -->
                        <div class="bg-primary border border-gray-800 rounded-none p-6">
                            <h2 class="text-2xl font-bold text-accent mb-4">Informasi Pelanggan</h2>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-gray-300 mb-2">Nama Lengkap *</label>
                                    <input type="text" 
                                           id="name"
                                           name="name" 
                                           value="{{ auth()->user()->name }}"
                                           required
                                           class="w-full px-4 py-3 bg-secondary border border-gray-700 text-accent placeholder-gray-500 focus:outline-none focus:border-accent transition duration-300">
                                </div>
                                
                                <div>
                                    <label for="address" class="block text-gray-300 mb-2">Alamat Pengiriman *</label>
                                    <textarea id="address"
                                              name="address" 
                                              rows="3"
                                              required
                                              class="w-full px-4 py-3 bg-secondary border border-gray-700 text-accent placeholder-gray-500 focus:outline-none focus:border-accent transition duration-300 resize-none"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-primary border border-gray-800 rounded-none p-6">
                            <h2 class="text-2xl font-bold text-accent mb-4">Metode Pembayaran</h2>
                            
                            <div class="space-y-3">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="payment_method" value="CASH" class="text-accent focus:ring-accent" checked>
                                    <span class="text-gray-300">Tunai</span>
                                </label>
                                
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="payment_method" value="TRANSFER" class="text-accent focus:ring-accent">
                                    <span class="text-gray-300">Transfer Bank</span>
                                </label>
                                
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="payment_method" value="QRIS" class="text-accent focus:ring-accent">
                                    <span class="text-gray-300">QRIS</span>
                                </label>
                            {{--     
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="payment_method" value="COD" class="text-accent focus:ring-accent">
                                    <span class="text-gray-300">Cash on Delivery (COD)</span>
                                </label> --}}
                            </div>

                            <!-- Bank Transfer Info (shown when TRANSFER is selected) -->
                            <div id="bank-info" class="mt-4 hidden">
                                <div class="bg-secondary border border-gray-700 rounded p-4">
                                    <h3 class="text-lg font-bold text-accent mb-3">Informasi Rekening</h3>
                                    <div class="space-y-2">
                                        <div class="flex justify-between">
                                            <span class="text-gray-400">Bank:</span>
                                            <span class="text-accent font-semibold">BRI</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-400">Atas Nama:</span>
                                            <span class="text-accent font-semibold">ZANOV</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-400">Nomor Rekening:</span>
                                            <span class="text-accent font-semibold">1234-5678-9012-3456</span>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-400 mt-3">Silakan transfer ke rekening di atas dan upload bukti pembayaran</p>
                                </div>
                            </div>

                            <!-- QRIS Info (shown when QRIS is selected) -->
                            <div id="qris-info" class="mt-4 hidden">
                                <div class="bg-secondary border border-gray-700 rounded p-4">
                                    <h3 class="text-lg font-bold text-accent mb-3">QRIS Payment</h3>
                                    <div class="text-center">
                                        <div class="w-48 h-48 bg-gray-900 border border-gray-700 mx-auto mb-3 flex items-center justify-center">
                                            <div class="text-center">
                                                <i data-feather="credit-card" class="w-16 h-16 text-gray-600 mx-auto mb-2"></i>
                                                <p class="text-gray-500 text-sm">QRIS Dummy</p>
                                                <p class="text-gray-600 text-xs mt-1">Scan untuk pembayaran</p>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-400">Scan QR code di atas untuk pembayaran QRIS</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Proof Upload (conditional) -->
                            <div id="proof-upload" class="mt-4 hidden">
                                <label class="block text-gray-300 mb-2">Bukti Pembayaran *</label>
                                <input type="file" 
                                       name="proof" 
                                       accept=".jpg,.jpeg,.png,.pdf"
                                       class="w-full px-4 py-3 bg-secondary border border-gray-700 text-accent file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-accent file:text-primary hover:file:bg-gray-200 transition duration-300">
                                <p class="text-sm text-gray-400 mt-2">Upload bukti pembayaran (JPG, PNG, PDF, maks 2MB)</p>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="bg-primary border border-gray-800 rounded-none p-6">
                            <h2 class="text-2xl font-bold text-accent mb-4">Catatan Tambahan</h2>
                            <textarea name="notes" 
                                      rows="3"
                                      placeholder="Instruksi khusus untuk pesanan Anda..."
                                      class="w-full px-4 py-3 bg-secondary border border-gray-700 text-accent placeholder-gray-500 focus:outline-none focus:border-accent transition duration-300 resize-none"></textarea>
                        </div>
                    </div>

                    <!-- Right Column - Order Summary -->
                    <div class="space-y-6">
                        <!-- Order Items -->
                        <div class="bg-primary border border-gray-800 rounded-none">
                            <div class="p-6 border-b border-gray-800">
                                <h2 class="text-2xl font-bold text-accent">Ringkasan Pesanan</h2>
                            </div>
                            
                            <div class="divide-y divide-gray-800 max-h-96 overflow-y-auto">
                                @foreach($cart->items as $item)
                                    <div class="p-4 flex items-center space-x-4">
                                        <!-- Product Image -->
                                        <div class="flex-shrink-0 w-16 h-16 bg-gray-900 border border-gray-800">
                                            @if($item->product->photo)
                                                <img src="{{ Storage::url($item->product->photo) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <i data-feather="image" class="w-6 h-6 text-gray-600"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Product Info -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-bold text-accent mb-1">{{ $item->product->name }}</h3>
                                            <p class="text-gray-400 text-sm">Jumlah: {{ $item->quantity }}</p>
                                            <p class="text-accent font-bold">Rp{{ number_format($item->price, 2) }}</p>
                                        </div>

                                        <!-- Subtotal -->
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-accent">Rp{{ number_format($item->subtotal, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Order Total -->
                            <div class="p-6 border-t border-gray-800">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-300">Subtotal:</span>
                                    <span class="text-accent">Rp{{ number_format($cart->total_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-4 border-t border-gray-800">
                                    <span class="text-xl text-gray-300 font-bold">Total Pembayaran:</span>
                                    <div class="text-right">
                                        <span class="text-2xl font-bold text-accent">Rp{{ number_format($cart->total_amount, 2) }}</span>
                                        <p class="text-sm text-gray-400 mt-1">Ongkos kirim ditanggung konsumen</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <button type="submit" 
                                class="w-full bg-accent text-primary px-8 py-4 text-xl uppercase tracking-wider font-bold hover:bg-gray-200 transition duration-300 text-center">
                            Selesaikan Pesanan
                        </button>

                        <!-- Back to Cart -->
                        <a href="{{ route('cart.index') }}" 
                           class="block w-full border border-accent text-accent px-8 py-4 text-center uppercase tracking-wider hover:bg-accent hover:text-primary transition duration-300">
                            Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </form>
        @else
            <!-- Empty Cart -->
            <div class="text-center py-20">
                <i data-feather="shopping-cart" class="w-24 h-24 text-gray-600 mx-auto mb-6"></i>
                <h3 class="text-3xl font-bold text-gray-400 mb-4 uppercase">Keranjang Anda Kosong</h3>
                <p class="text-gray-500 text-lg mb-8">Mulai tambahkan produk ke keranjang Anda</p>
                <a href="{{ route('catalogue') }}" 
                   class="border border-accent text-accent px-8 py-3 uppercase tracking-wider hover:bg-accent hover:text-primary transition duration-300">
                    Lihat Produk
                </a>
            </div>
        @endif
    </div>
</section>

<script>
// Show/hide payment info sections based on payment method
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const proofUpload = document.getElementById('proof-upload');
        const bankInfo = document.getElementById('bank-info');
        const qrisInfo = document.getElementById('qris-info');
        
        // Hide all sections first
        bankInfo.classList.add('hidden');
        qrisInfo.classList.add('hidden');
        proofUpload.classList.add('hidden');
        
        // Show relevant sections based on payment method
        if (this.value === 'TRANSFER') {
            bankInfo.classList.remove('hidden');
            proofUpload.classList.remove('hidden');
            proofUpload.querySelector('input[type="file"]').required = true;
        } else if (this.value === 'QRIS') {
            qrisInfo.classList.remove('hidden');
            proofUpload.classList.remove('hidden');
            proofUpload.querySelector('input[type="file"]').required = true;
        } else {
            proofUpload.querySelector('input[type="file"]').required = false;
        }
    });
});

// Initialize feather icons
feather.replace();
</script>
@endsection