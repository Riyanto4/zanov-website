{{-- resources/views/transaction/show.blade.php --}}
@extends('layouts.guest.main')

@section('content')
<section class="py-20 px-4 sm:px-6 lg:px-8 bg-secondary min-h-screen">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-4 uppercase tracking-tight text-accent">Order Details</h1>
            <p class="text-accent">Reference: {{ $transaction->reference_no }}</p>
        </div>

        <div class="space-y-6">
            <!-- Order Summary -->
            <div class="bg-primary border border-gray-800 rounded-none p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Order Information -->
                    <div>
                        <h2 class="text-2xl font-bold text-accent mb-4">Order Information</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-accent">Reference No:</span>
                                <span class="text-accent font-bold">{{ $transaction->reference_no }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-accent">Order Date:</span>
                                <span class="text-accent">{{ $transaction->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-accent">Payment Method:</span>
                                <span class="text-accent">{{ $transaction->payment_method }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-accent">Status:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $transaction->payment_status === 'PAID' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $transaction->payment_status === 'PENDING' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $transaction->payment_status === 'CANCELED' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $transaction->payment_status === 'REFUNDED' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $transaction->payment_status }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div>
                        <h2 class="text-2xl font-bold text-accent mb-4">Customer Information</h2>
                        <div class="space-y-3">
                            <div>
                                <span class="text-accent">Name:</span>
                                <p class="text-accent">{{ $transaction->name }}</p>
                            </div>
                            <div>
                                <span class="text-accent">Address:</span>
                                <p class="text-accent">{{ $transaction->address }}</p>
                            </div>
                            @if($transaction->notes)
                            <div>
                                <span class="text-accent">Notes:</span>
                                <p class="text-accent">{{ $transaction->notes }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Proof of Payment -->
                @if($transaction->proof)
                <div class="mt-6 pt-6 border-t border-gray-800">
                    <h3 class="text-lg font-bold text-accent mb-3">Proof of Payment</h3>
                    <div class="flex items-center space-x-4">
                        @if(in_array(pathinfo($transaction->proof, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                            <img src="{{ Storage::url($transaction->proof) }}" 
                                 alt="Payment Proof" 
                                 class="w-32 h-32 object-cover border border-gray-700 cursor-pointer"
                                 onclick="openModal('{{ Storage::url($transaction->proof) }}')">
                        @else
                            <div class="flex items-center space-x-3 p-4 border border-gray-700">
                                <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-accent font-bold">Payment Proof</p>
                                    <a href="{{ Storage::url($transaction->proof) }}" 
                                       target="_blank" 
                                       class="text-sm text-gray-400 hover:text-accent transition duration-300">
                                        View Document
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Order Items -->
            <div class="bg-primary border border-gray-800 rounded-none">
                <div class="p-6 border-b border-gray-800">
                    <h2 class="text-2xl font-bold text-accent">Order Items</h2>
                </div>
                
                <div class="divide-y divide-gray-800">
                    @foreach($transaction->items as $item)
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row md:items-start space-y-4 md:space-y-0 md:space-x-6">
                                <!-- Product Image -->
                                <div class="flex-shrink-0 w-24 h-24 bg-gray-900 border border-gray-800">
                                    @if($item->product->image)
                                        <img src="{{ Storage::url($item->product->image) }}" 
                                             alt="{{ $item->product->name }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-bold text-accent mb-2">{{ $item->product->name }}</h3>
                                    <p class="text-gray-400 text-sm mb-1">Quantity: {{ $item->quantity }}</p>
                                    <p class="text-accent mb-2">Rp {{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }}</p>
                                    <p class="text-xl font-bold text-accent">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                    
                                    <!-- Display average rating -->
                                    @if($item->product->averageRating())
                                        <div class="flex items-center mt-3">
                                            <div class="flex">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($item->product->averageRating()))
                                                        <span class="text-yellow-400">★</span>
                                                    @elseif($i <= $item->product->averageRating())
                                                        <span class="text-yellow-400">★</span>
                                                    @else
                                                        <span class="text-gray-600">★</span>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="ml-2 text-sm text-gray-400">
                                                {{ number_format($item->product->averageRating(), 1) }} ({{ $item->product->ratings()->count() }} reviews)
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Rating Form - Only show if transaction is PAID -->
                            @if($transaction->payment_status === 'PAID')
                                @php
                                    $existingRating = $item->product->ratings->first();
                                @endphp
                                @include('components.rating-form', [
                                    'transaction' => $transaction,
                                    'product' => $item->product,
                                    'existingRating' => $existingRating
                                ])
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Order Total -->
                <div class="p-6 border-t border-gray-800">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-accent">Subtotal:</span>
                        <span class="text-accent">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-accent">Shipping:</span>
                        <span class="text-accent">Included</span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-gray-800">
                        <span class="text-xl text-accent font-bold">Total Amount:</span>
                        <span class="text-3xl font-bold text-accent">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-900 border border-green-700 text-green-100 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-900 border border-red-700 text-red-100 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-between gap-4">
                <a href="{{ route('transactions.index') }}" 
                   class="flex-1 border border-accent text-accent px-6 py-3 text-center uppercase tracking-wider hover:bg-accent hover:text-primary transition duration-300 font-bold">
                    ← Back to History
                </a>
                <a href="{{ route('catalogue') }}" 
                   class="flex-1 bg-accent text-primary px-6 py-3 text-center uppercase tracking-wider font-bold hover:bg-gray-200 transition duration-300">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center p-4">
    <div class="max-w-4xl max-h-full">
        <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-white hover:text-accent transition duration-300">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>

<script>
function openModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Handle star rating click
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[name="rating"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const stars = this.closest('.flex').querySelectorAll('label');
            const ratingValue = parseInt(this.value);
            
            stars.forEach((star, index) => {
                const starNumber = 5 - index;
                if (starNumber <= ratingValue) {
                    star.classList.remove('text-gray-600');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-600');
                }
            });
        });
    });
    
    // Initialize star display based on existing ratings
    document.querySelectorAll('input[name="rating"]:checked').forEach(radio => {
        radio.dispatchEvent(new Event('change'));
    });
});
</script>
@endsection