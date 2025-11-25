@extends('layouts.guest.main')

@section('content')
<section class="py-20 px-4 sm:px-6 lg:px-8 bg-secondary min-h-screen">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-4 uppercase tracking-tight">Order Details</h1>
            <p class="text-gray-300">Reference: {{ $transaction->reference_no }}</p>
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
                                <span class="text-gray-300">Reference No:</span>
                                <span class="text-accent font-bold">{{ $transaction->reference_no }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-300">Order Date:</span>
                                <span class="text-accent">{{ $transaction->created_at->format('M d, Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-300">Payment Method:</span>
                                <span class="text-accent">{{ $transaction->payment_method }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-300">Status:</span>
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
                                <span class="text-gray-300">Name:</span>
                                <p class="text-accent">{{ $transaction->name }}</p>
                            </div>
                            <div>
                                <span class="text-gray-300">Address:</span>
                                <p class="text-accent">{{ $transaction->address }}</p>
                            </div>
                            @if($transaction->notes)
                            <div>
                                <span class="text-gray-300">Notes:</span>
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
                                <i data-feather="file-text" class="w-8 h-8 text-accent"></i>
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
                        <div class="p-6 flex items-center space-x-6">
                            <!-- Product Image -->
                            <div class="flex-shrink-0 w-20 h-20 bg-gray-900 border border-gray-800">
                                @if($item->product->photo)
                                    <img src="{{ Storage::url($item->product->photo) }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i data-feather="image" class="w-8 h-8 text-gray-600"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-accent mb-2">{{ $item->product->name }}</h3>
                                <p class="text-gray-400 text-sm mb-1">Code: {{ $item->product->code }}</p>
                                <p class="text-accent">${{ number_format($item->price, 2) }} x {{ $item->quantity }}</p>
                            </div>

                            <!-- Subtotal -->
                            <div class="text-right">
                                <p class="text-2xl font-bold text-accent">${{ number_format($item->subtotal, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Total -->
                <div class="p-6 border-t border-gray-800">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-300">Subtotal:</span>
                        <span class="text-accent">${{ number_format($transaction->total_amount, 2) }}</span>
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
                        <span class="text-3xl font-bold text-accent">${{ number_format($transaction->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between space-x-4">
                <a href="{{ route('transactions.index') }}" 
                   class="flex-1 border border-accent text-accent px-6 py-3 text-center uppercase tracking-wider hover:bg-accent hover:text-primary transition duration-300">
                    Back to History
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
        <button onclick="closeModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 transition duration-300">
            <i data-feather="x" class="w-8 h-8"></i>
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

feather.replace();
</script>
@endsection