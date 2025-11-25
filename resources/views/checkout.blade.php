@extends('layouts.guest.main')

@section('content')
<section class="py-20 px-4 sm:px-6 lg:px-8 bg-secondary min-h-screen">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-4 uppercase tracking-tight">Checkout</h1>
            <p class="text-gray-300">Complete your purchase</p>
        </div>

        @if($cart && $cart->items->count() > 0)
            <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column - Customer Info & Payment -->
                    <div class="space-y-6">
                        <!-- Customer Information -->
                        <div class="bg-primary border border-gray-800 rounded-none p-6">
                            <h2 class="text-2xl font-bold text-accent mb-4">Customer Information</h2>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-gray-300 mb-2">Full Name *</label>
                                    <input type="text" 
                                           id="name"
                                           name="name" 
                                           value="{{ auth()->user()->name }}"
                                           required
                                           class="w-full px-4 py-3 bg-secondary border border-gray-700 text-accent placeholder-gray-500 focus:outline-none focus:border-accent transition duration-300">
                                </div>
                                
                                <div>
                                    <label for="address" class="block text-gray-300 mb-2">Delivery Address *</label>
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
                            <h2 class="text-2xl font-bold text-accent mb-4">Payment Method</h2>
                            
                            <div class="space-y-3">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="payment_method" value="CASH" class="text-accent focus:ring-accent" checked>
                                    <span class="text-gray-300">Cash</span>
                                </label>
                                
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="payment_method" value="TRANSFER" class="text-accent focus:ring-accent">
                                    <span class="text-gray-300">Bank Transfer</span>
                                </label>
                                
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="payment_method" value="QRIS" class="text-accent focus:ring-accent">
                                    <span class="text-gray-300">QRIS</span>
                                </label>
                                
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="payment_method" value="COD" class="text-accent focus:ring-accent">
                                    <span class="text-gray-300">Cash on Delivery (COD)</span>
                                </label>
                            </div>

                            <!-- Proof Upload (conditional) -->
                            <div id="proof-upload" class="mt-4 hidden">
                                <label class="block text-gray-300 mb-2">Payment Proof *</label>
                                <input type="file" 
                                       name="proof" 
                                       accept=".jpg,.jpeg,.png,.pdf"
                                       class="w-full px-4 py-3 bg-secondary border border-gray-700 text-accent file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-accent file:text-primary hover:file:bg-gray-200 transition duration-300">
                                <p class="text-sm text-gray-400 mt-2">Upload proof of payment (JPG, PNG, PDF, max 2MB)</p>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="bg-primary border border-gray-800 rounded-none p-6">
                            <h2 class="text-2xl font-bold text-accent mb-4">Additional Notes</h2>
                            <textarea name="notes" 
                                      rows="3"
                                      placeholder="Any special instructions for your order..."
                                      class="w-full px-4 py-3 bg-secondary border border-gray-700 text-accent placeholder-gray-500 focus:outline-none focus:border-accent transition duration-300 resize-none"></textarea>
                        </div>
                    </div>

                    <!-- Right Column - Order Summary -->
                    <div class="space-y-6">
                        <!-- Order Items -->
                        <div class="bg-primary border border-gray-800 rounded-none">
                            <div class="p-6 border-b border-gray-800">
                                <h2 class="text-2xl font-bold text-accent">Order Summary</h2>
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
                                            <p class="text-gray-400 text-sm">Qty: {{ $item->quantity }}</p>
                                            <p class="text-accent font-bold">${{ number_format($item->price, 2) }}</p>
                                        </div>

                                        <!-- Subtotal -->
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-accent">${{ number_format($item->subtotal, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Order Total -->
                            <div class="p-6 border-t border-gray-800">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-300">Subtotal:</span>
                                    <span class="text-accent">${{ number_format($cart->total_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-300">Shipping:</span>
                                    <span class="text-accent">$0.00</span>
                                </div>
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-gray-300">Tax:</span>
                                    <span class="text-accent">$0.00</span>
                                </div>
                                <div class="flex justify-between items-center pt-4 border-t border-gray-800">
                                    <span class="text-xl text-gray-300 font-bold">Total Amount:</span>
                                    <span class="text-2xl font-bold text-accent">${{ number_format($cart->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <button type="submit" 
                                class="w-full bg-accent text-primary px-8 py-4 text-xl uppercase tracking-wider font-bold hover:bg-gray-200 transition duration-300 text-center">
                            Complete Order
                        </button>

                        <!-- Back to Cart -->
                        <a href="{{ route('cart.index') }}" 
                           class="block w-full border border-accent text-accent px-8 py-4 text-center uppercase tracking-wider hover:bg-accent hover:text-primary transition duration-300">
                            Back to Cart
                        </a>
                    </div>
                </div>
            </form>
        @else
            <!-- Empty Cart -->
            <div class="text-center py-20">
                <i data-feather="shopping-cart" class="w-24 h-24 text-gray-600 mx-auto mb-6"></i>
                <h3 class="text-3xl font-bold text-gray-400 mb-4 uppercase">Your Cart is Empty</h3>
                <p class="text-gray-500 text-lg mb-8">Start adding some products to your cart</p>
                <a href="{{ route('catalogue') }}" 
                   class="border border-accent text-accent px-8 py-3 uppercase tracking-wider hover:bg-accent hover:text-primary transition duration-300">
                    Browse Products
                </a>
            </div>
        @endif
    </div>
</section>

<script>
// Show/hide proof upload based on payment method
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const proofUpload = document.getElementById('proof-upload');
        if (this.value === 'TRANSFER' || this.value === 'QRIS') {
            proofUpload.classList.remove('hidden');
            // Make proof required for these methods
            proofUpload.querySelector('input[type="file"]').required = true;
        } else {
            proofUpload.classList.add('hidden');
            proofUpload.querySelector('input[type="file"]').required = false;
        }
    });
});

// Initialize feather icons
feather.replace();
</script>
@endsection