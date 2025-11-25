@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Transactions Management</h1>
        <div class="flex space-x-4">
            <a href="{{ route('dashboard') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Dashboard
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verified By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($transactions as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $transaction->reference_no }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $transaction->name }}</div>
                                <div class="text-sm text-gray-500">{{ $transaction->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $transaction->payment_method == 'CASH' ? 'bg-green-100 text-green-800' : 
                                       ($transaction->payment_method == 'TRANSFER' ? 'bg-blue-100 text-blue-800' : 
                                       ($transaction->payment_method == 'QRIS' ? 'bg-purple-100 text-purple-800' : 'bg-orange-100 text-orange-800')) }}">
                                    {{ $transaction->payment_method }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $transaction->payment_status == 'PAID' ? 'bg-green-100 text-green-800' : 
                                       ($transaction->payment_status == 'PENDING' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($transaction->payment_status == 'CANCELED' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ $transaction->payment_status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaction->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($transaction->verifier)
                                    {{ $transaction->verifier->name }}
                                    <div class="text-xs text-gray-400">
                                        {{ $transaction->verified_at }}
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="{{ route('transactions.show', $transaction) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 mr-2">
                                    View Details
                                </a>
                                
                                @if($transaction->proof)
                                    <a href="{{ Storage::url($transaction->proof) }}" 
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-900 mr-2">
                                        View Proof
                                    </a>
                                @endif
                                
                                @if($transaction->payment_status === 'PENDING')
                                    <form action="{{ route('transactions.verify', $transaction) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" 
                                                class="text-green-600 hover:text-green-900 mr-2"
                                                onclick="return confirm('Are you sure you want to verify this transaction?')">
                                            Verify
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('transactions.cancel', $transaction) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Are you sure you want to cancel this transaction? Stock will be restored.')">
                                            Cancel
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-xs">
                                        {{ $transaction->payment_status }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                No transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection