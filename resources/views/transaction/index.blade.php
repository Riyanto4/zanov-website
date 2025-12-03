@extends('layouts.guest.main')

@section('content')
<section class="py-20 px-4 sm:px-6 lg:px-8 bg-secondary min-h-screen">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-4 uppercase tracking-tight">Order History</h1>
            <p class="text-accent">Track and view your past orders</p>
        </div>

        @if($transactions->count() > 0)
            <div class="bg-primary border border-gray-800 rounded-none">
                <!-- Table Header -->
                <div class="grid grid-cols-12 gap-4 p-6 border-b border-gray-800 font-bold text-accent uppercase text-sm">
                    <div class="col-span-3">Order Info</div>
                    <div class="col-span-2">Date</div>
                    <div class="col-span-2">Payment Method</div>
                    <div class="col-span-2">Status</div>
                    <div class="col-span-2 text-right">Total</div>
                    <div class="col-span-1 text-center">Action</div>
                </div>

                <!-- Transactions List -->
                <div class="divide-y divide-gray-800">
                    @foreach($transactions as $transaction)
                        <div class="grid grid-cols-12 gap-4 p-6 items-center transition duration-300">
                            <!-- Order Info -->
                            <div class="col-span-3">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 w-12 h-12 bg-gray-800 border border-gray-700 flex items-center justify-center">
                                        <i data-feather="package" class="w-6 h-6 text-accent"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-accent">{{ $transaction->reference_no }}</p>
                                        <p class="text-accent text-sm">{{ $transaction->items->count() }} items</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Date -->
                            <div class="col-span-2">
                                <p class="text-accent">{{ $transaction->created_at->format('M d, Y') }}</p>
                                <p class="text-accent text-sm">{{ $transaction->created_at->format('H:i') }}</p>
                            </div>

                            <!-- Payment Method -->
                            <div class="col-span-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $transaction->payment_method === 'CASH' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $transaction->payment_method === 'TRANSFER' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $transaction->payment_method === 'QRIS' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $transaction->payment_method === 'COD' ? 'bg-orange-100 text-orange-800' : '' }}">
                                    {{ $transaction->payment_method }}
                                </span>
                            </div>

                            <!-- Status -->
                            <div class="col-span-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $transaction->payment_status === 'PAID' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $transaction->payment_status === 'PENDING' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $transaction->payment_status === 'CANCELED' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $transaction->payment_status === 'REFUNDED' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $transaction->payment_status }}
                                </span>
                            </div>

                            <!-- Total -->
                            <div class="col-span-2 text-right">
                                <p class="text-xl font-bold text-accent">${{ number_format($transaction->total_amount, 2) }}</p>
                            </div>

                            <!-- Action -->
                            <div class="col-span-1 text-center">
                                <a href="{{ route('transactions.show', $transaction->id) }}" 
                                   class="inline-flex items-center text-accent transition duration-300"
                                   title="View Order Details">
                                    <i data-feather="eye" class="w-5 h-5"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($transactions->hasPages())
                    <div class="p-6 border-t border-gray-800">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-20">
                <i data-feather="shopping-bag" class="w-24 h-24 text-gray-600 mx-auto mb-6"></i>
                <h3 class="text-3xl font-bold text-accent mb-4 uppercase">No Orders Yet</h3>
                <p class="text-gray-500 text-lg mb-8">You haven't placed any orders yet</p>
                <a href="{{ route('catalogue') }}" 
                   class="border border-accent text-accent px-8 py-3 uppercase tracking-wider transition duration-300">
                    Start Shopping
                </a>
            </div>
        @endif
    </div>
</section>

<script>
    feather.replace();
</script>
@endsection