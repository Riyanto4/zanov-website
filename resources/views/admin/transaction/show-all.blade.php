@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Manajemen Transaksi</h1>
        <div class="flex space-x-4">
            <a href="{{ route('dashboard') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali ke Dashboard
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

    @php
        $statusLabels = [
            'PAID' => 'Lunas',
            'PENDING' => 'Menunggu',
            'CANCELED' => 'Dibatalkan',
            'REFUNDED' => 'Dikembalikan',
        ];
        $paymentLabels = [
            'CASH' => 'Tunai',
            'TRANSFER' => 'Transfer',
            'QRIS' => 'QRIS',
        ];
    @endphp

    <!-- Bagian Statistik dan Grafik -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Kartu Total Transaksi -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Transaksi</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">
                        {{ $transactions->total() }}
                    </p>
                </div>
                <div class="p-3 rounded-full bg-blue-100">
                    <i data-feather="shopping-cart" class="text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Kartu Transaksi Menunggu -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Menunggu Verifikasi</p>
                    <p class="mt-1 text-2xl font-semibold text-yellow-600">
                        {{ \App\Models\Transaction::where('payment_status', 'PENDING')->count() }}
                    </p>
                </div>
                <div class="p-3 rounded-full bg-yellow-100">
                    <i data-feather="clock" class="text-yellow-600"></i>
                </div>
            </div>
        </div>

        <!-- Kartu Total Pendapatan -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Pendapatan (Lunas)</p>
                    <p class="mt-1 text-2xl font-semibold text-green-600">
                        Rp {{ number_format(\App\Models\Transaction::where('payment_status', 'PAID')->sum('total_amount'), 0, ',', '.') }}
                    </p>
                </div>
                <div class="p-3 rounded-full bg-green-100">
                    <i data-feather="dollar-sign" class="text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Grafik -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Grafik Distribusi Status -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Distribusi Status Transaksi</h2>
                <span class="text-sm text-gray-600">Data real-time</span>
            </div>
            <div>
                {!! $chart->container() !!}
            </div>
            <div class="mt-4 text-sm text-gray-500">
                <p>Grafik ini menampilkan distribusi semua transaksi berdasarkan status pembayaran.</p>
            </div>
        </div>

        <!-- Legenda dan Statistik -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Rincian Status</h2>
            <div class="space-y-4">
                @php
                    $statuses = [
                        'PAID' => ['color' => 'bg-green-100 text-green-800', 'icon' => 'check-circle'],
                        'PENDING' => ['color' => 'bg-yellow-100 text-yellow-800', 'icon' => 'clock'],
                        'CANCELED' => ['color' => 'bg-red-100 text-red-800', 'icon' => 'x-circle'],
                        'REFUNDED' => ['color' => 'bg-gray-100 text-gray-800', 'icon' => 'alert-circle'],
                    ];
                    
                    foreach($statuses as $status => $style):
                        $count = \App\Models\Transaction::where('payment_status', $status)->count();
                        $percentage = $transactions->total() > 0 ? round(($count / $transactions->total()) * 100, 1) : 0;
                @endphp
                <div class="flex items-center justify-between p-3 rounded-lg {{ $style['color'] }}">
                    <div class="flex items-center">
                        <i data-feather="{{ $style['icon'] }}" class="w-5 h-5 mr-3"></i>
                        <span class="font-medium">{{ $statusLabels[$status] ?? $status }}</span>
                    </div>
                    <div class="text-right">
                        <div class="font-bold">{{ $count }}</div>
                        <div class="text-sm opacity-75">{{ $percentage }}%</div>
                    </div>
                </div>
                @php endforeach; @endphp
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="text-sm text-gray-600">
                    <p><strong>Catatan:</strong></p>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        <li>Transaksi <span class="text-yellow-600">Menunggu</span> memerlukan verifikasi</li>
                        <li>Transaksi <span class="text-green-600">Lunas</span> sudah selesai</li>
                        <li>Transaksi <span class="text-red-600">Dibatalkan</span> telah dibatalkan</li>
                        <li>Klik "Verifikasi" untuk menyetujui transaksi yang tertunda</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">
    <form method="GET" action="{{ route('transactions.all') }}" id="filterForm">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <!-- Search -->
            <div>
            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" 
                       name="search" 
                       id="search" 
                       value="{{ $filters['search'] ?? '' }}"
                       placeholder="No. Referensi, Pelanggan, Email"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" 
                        id="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="all" {{ ($filters['status'] ?? 'all') == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="PENDING" {{ ($filters['status'] ?? '') == 'PENDING' ? 'selected' : '' }}>Menunggu</option>
                    <option value="PAID" {{ ($filters['status'] ?? '') == 'PAID' ? 'selected' : '' }}>Lunas</option>
                    <option value="CANCELED" {{ ($filters['status'] ?? '') == 'CANCELED' ? 'selected' : '' }}>Dibatalkan</option>
                    <option value="REFUNDED" {{ ($filters['status'] ?? '') == 'REFUNDED' ? 'selected' : '' }}>Dikembalikan</option>
                </select>
            </div>

            <!-- Payment Method Filter -->
            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                <select name="payment_method" 
                        id="payment_method"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="all" {{ ($filters['payment_method'] ?? 'all') == 'all' ? 'selected' : '' }}>Semua Metode</option>
                    <option value="CASH" {{ ($filters['payment_method'] ?? '') == 'CASH' ? 'selected' : '' }}>Tunai</option>
                    <option value="TRANSFER" {{ ($filters['payment_method'] ?? '') == 'TRANSFER' ? 'selected' : '' }}>Transfer</option>
                    <option value="QRIS" {{ ($filters['payment_method'] ?? '') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>

            <!-- Sort By -->
            <div>
                <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-1">Urutkan Berdasarkan</label>
                <select name="sort_by" 
                        id="sort_by"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="created_at" {{ ($filters['sort_by'] ?? 'created_at') == 'created_at' ? 'selected' : '' }}>Tanggal</option>
                    <option value="total_amount" {{ ($filters['sort_by'] ?? '') == 'total_amount' ? 'selected' : '' }}>Total Pembayaran</option>
                    <option value="reference_no" {{ ($filters['sort_by'] ?? '') == 'reference_no' ? 'selected' : '' }}>No. Referensi</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <!-- Date Range -->
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dari</label>
                <input type="date" 
                       name="date_from" 
                       id="date_from" 
                       value="{{ $filters['date_from'] ?? '' }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Sampai</label>
                <input type="date" 
                       name="date_to" 
                       id="date_to" 
                       value="{{ $filters['date_to'] ?? '' }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Sort Order -->
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
                <select name="sort_order" 
                        id="sort_order"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="desc" {{ ($filters['sort_order'] ?? 'desc') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                    <option value="asc" {{ ($filters['sort_order'] ?? '') == 'asc' ? 'selected' : '' }}>Terlama</option>
                </select>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <div class="text-sm text-gray-500">
                Menampilkan {{ $transactions->firstItem() ?? 0 }} sampai {{ $transactions->lastItem() ?? 0 }} dari {{ $transactions->total() }} hasil
            </div>
            
            <div class="flex space-x-2">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i data-feather="filter" class="w-4 h-4 mr-2"></i>
                    Terapkan Filter
                </button>
                
                <a href="{{ route('transactions.all') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i data-feather="refresh-cw" class="w-4 h-4 mr-2"></i>
                    Atur Ulang Filter
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Active Filters Badges (tambahkan setelah filter section) -->
@if(collect($filters)->filter(fn($value, $key) => $key !== 'sort_by' && $key !== 'sort_order' && !empty($value))->count() > 0)
<div class="mb-6">
    <div class="flex flex-wrap gap-2 items-center">
        <span class="text-sm font-medium text-gray-700">Filter Aktif:</span>
        @if($filters['search'])
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                Pencarian: "{{ $filters['search'] }}"
                <a href="{{ route('transactions.all', array_merge(request()->except('search'), ['page' => 1])) }}" 
                   class="ml-1 text-blue-600 hover:text-blue-800">
                    ×
                </a>
            </span>
        @endif
        
        @if($filters['status'] && $filters['status'] !== 'all')
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                Status: {{ $statusLabels[$filters['status']] ?? $filters['status'] }}
                <a href="{{ route('transactions.all', array_merge(request()->except('status'), ['page' => 1])) }}" 
                   class="ml-1 text-purple-600 hover:text-purple-800">
                    ×
                </a>
            </span>
        @endif
        
        @if($filters['payment_method'] && $filters['payment_method'] !== 'all')
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                Pembayaran: {{ $paymentLabels[$filters['payment_method']] ?? $filters['payment_method'] }}
                <a href="{{ route('transactions.all', array_merge(request()->except('payment_method'), ['page' => 1])) }}" 
                   class="ml-1 text-green-600 hover:text-green-800">
                    ×
                </a>
            </span>
        @endif
        
        @if($filters['date_from'])
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                Dari: {{ \Carbon\Carbon::parse($filters['date_from'])->format('d M Y') }}
                <a href="{{ route('transactions.all', array_merge(request()->except('date_from'), ['page' => 1])) }}" 
                   class="ml-1 text-yellow-600 hover:text-yellow-800">
                    ×
                </a>
            </span>
        @endif
        
        @if($filters['date_to'])
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                Sampai: {{ \Carbon\Carbon::parse($filters['date_to'])->format('d M Y') }}
                <a href="{{ route('transactions.all', array_merge(request()->except('date_to'), ['page' => 1])) }}" 
                   class="ml-1 text-yellow-600 hover:text-yellow-800">
                    ×
                </a>
            </span>
        @endif
    </div>
</div>
@endif

<style>
/* Hide the calendar icon in date inputs */
input[type="date"]::-webkit-calendar-picker-indicator {
    cursor: pointer;
    opacity: 0.6;
    filter: invert(0.5);
}

/* Style for active filter badges */
.filter-badge {
    transition: all 0.2s ease;
}

.filter-badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .filter-form .grid {
        grid-template-columns: 1fr !important;
    }
}
</style>

    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <!-- Table header and body tetap sama seperti sebelumnya -->
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Referensi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pembayaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode Pembayaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pembayaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diverifikasi Oleh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($transactions as $transaction)
                        <tr class="hover:bg-gray-50">
                            <!-- Table rows tetap sama seperti sebelumnya -->
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
                                    {{ $paymentLabels[$transaction->payment_method] ?? $transaction->payment_method }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $transaction->payment_status == 'PAID' ? 'bg-green-100 text-green-800' : 
                                       ($transaction->payment_status == 'PENDING' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($transaction->payment_status == 'CANCELED' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ $statusLabels[$transaction->payment_status] ?? $transaction->payment_status }}
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
                                    Lihat Detail
                                </a>
                                
                                @if($transaction->proof)
                                    <a href="{{ Storage::url($transaction->proof) }}" 
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-900 mr-2">
                                        Lihat Bukti
                                    </a>
                                @endif
                                
                                @if($transaction->payment_status === 'PENDING')
                                    <form action="{{ route('transactions.verify', $transaction) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" 
                                                class="text-green-600 hover:text-green-900 mr-2"
                                                onclick="return confirm('Yakin ingin memverifikasi transaksi ini?')">
                                            Verifikasi
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('transactions.cancel', $transaction) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Yakin ingin membatalkan transaksi ini? Stok akan dikembalikan.')">
                                            Batalkan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-xs">
                                        {{ $statusLabels[$transaction->payment_status] ?? $transaction->payment_status }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                Tidak ada transaksi ditemukan.
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

{{-- Date validation script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateFrom = document.getElementById('date_from');
    const dateTo = document.getElementById('date_to');
    const form = document.getElementById('filterForm');
    
    if (dateFrom && dateTo) {
        // Set max date for date_from
        dateFrom.addEventListener('change', function() {
            dateTo.min = this.value;
        });
        
        // Set min date for date_to
        dateTo.addEventListener('change', function() {
            dateFrom.max = this.value;
        });
    }
    
    // Auto-submit when select changes (optional)
    const autoSubmitSelects = ['status', 'payment_method', 'sort_by', 'sort_order'];
    autoSubmitSelects.forEach(selectId => {
        const select = document.getElementById(selectId);
        if (select) {
            select.addEventListener('change', function() {
                // Submit form only if not in the middle of filtering
                if (!form.classList.contains('filtering')) {
                    form.submit();
                }
            });
        }
    });
});
</script>
@endsection
