@extends('layouts.main')

@section('content')
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow-sm border border-zanov-orange/20">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-zanov-dark/60">Total Penjualan Hari Ini</p>
                <p class="mt-1 text-2xl font-semibold text-zanov-orange">{{ $todaySales }}</p>
            </div>
            <div class="p-3 rounded-full bg-zanov-orange/10">
                <i data-feather="shopping-cart" class="text-zanov-orange"></i>
            </div>
        </div>
        <p class="mt-2 text-xs text-zanov-dark/60 {{ $salesPercentageChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
            @if($salesPercentageChange >= 0)
                <i data-feather="trending-up" class="w-3 h-3 inline mr-1"></i>
            @else
                <i data-feather="trending-down" class="w-3 h-3 inline mr-1"></i>
            @endif
            {{ $salesPercentageChange >= 0 ? '+' : '' }}{{ $salesPercentageChange }}% dari kemarin
        </p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-sm border border-zanov-orange/20">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-zanov-dark/60">Pendapatan Bulanan</p>
                <p class="mt-1 text-2xl font-semibold text-zanov-orange">Rp {{ number_format($currentMonthRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="p-3 rounded-full bg-zanov-orange/10">
                <i data-feather="dollar-sign" class="text-zanov-orange"></i>
            </div>
        </div>
        <p class="mt-2 text-xs text-zanov-dark/60 {{ $revenuePercentageChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
            @if($revenuePercentageChange >= 0)
                <i data-feather="trending-up" class="w-3 h-3 inline mr-1"></i>
            @else
                <i data-feather="trending-down" class="w-3 h-3 inline mr-1"></i>
            @endif
            {{ $revenuePercentageChange >= 0 ? '+' : '' }}{{ $revenuePercentageChange }}% dari bulan lalu
        </p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-sm border border-zanov-orange/20">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-zanov-dark/60">Produk Terlaris</p>
                @if($bestSellingProduct)
                    <p class="mt-1 text-lg font-semibold text-zanov-orange truncate">{{ $bestSellingProduct->name }}</p>
                @else
                    <p class="mt-1 text-lg font-semibold text-zanov-orange">-</p>
                @endif
            </div>
            <div class="p-3 rounded-full bg-zanov-orange/10">
                <i data-feather="award" class="text-zanov-orange"></i>
            </div>
        </div>
        @if($bestSellingProduct)
            <p class="mt-2 text-xs text-zanov-dark/60">Terjual {{ $bestSellingProduct->total_quantity }} unit</p>
        @else
            <p class="mt-2 text-xs text-zanov-dark/60">Belum ada data penjualan</p>
        @endif
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-sm border border-zanov-orange/20 cursor-pointer hover:bg-zanov-orange/5 transition-colors" 
         onclick="window.location.href='{{ route('products.index') }}?filter=low-stock'">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-zanov-dark/60">Stok Hampir Habis</p>
                <p class="mt-1 text-2xl font-semibold text-zanov-orange">{{ $lowStockProducts }}</p>
            </div>
            <div class="p-3 rounded-full bg-zanov-orange/10">
                <i data-feather="alert-circle" class="text-zanov-orange"></i>
            </div>
        </div>
        <p class="mt-2 text-xs text-zanov-dark/60 flex items-center">
            <i data-feather="arrow-right" class="w-3 h-3 mr-1"></i>
            Klik untuk lihat produk perlu restock
        </p>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Tren Penjualan Harian -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-zanov-orange/20">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-zanov-dark">Tren Penjualan Harian</h2>
            <span class="text-sm text-zanov-dark/60">7 Hari Terakhir</span>
        </div>
        <div>
            {!! $salesTrendChart->container() !!}
        </div>
    </div>
    
    <!-- Grafik Per Status -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-zanov-orange/20">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-zanov-dark">Distribusi Status Transaksi</h2>
            <span class="text-sm text-zanov-dark/60">Total Semua Transaksi</span>
        </div>
        <div>
            {!! $statusChart->container() !!}
        </div>
    </div>
</div>

<!-- Rating Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Top Rating -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-zanov-orange/20">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-zanov-dark">Top 5 Produk dengan Rating Tertinggi</h2>
            <span class="text-sm text-zanov-dark/60">Min. 3 ulasan</span>
        </div>
        <div>
            {!! $bestRatingChart->container() !!}
        </div>
    </div>
    
    <!-- Worst Rating -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-zanov-orange/20">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-zanov-dark">5 Produk dengan Rating Terendah</h2>
            <span class="text-sm text-zanov-dark/60">Perlu evaluasi</span>
        </div>
        <div>
            {!! $worstRatingChart->container() !!}
        </div>
    </div>
</div>

<!-- Top Produk Terlaris (List Bar) -->
<div class="bg-white p-6 rounded-lg shadow-sm border border-zanov-orange/20 mb-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold text-zanov-dark">Top 5 Produk Terlaris</h2>
        <span class="text-sm text-zanov-dark/60">Total Unit Terjual</span>
    </div>
    
    <div class="space-y-4">
        @forelse($bestSellingProducts as $index => $product)
            @php
                // Hitung persentase relatif terhadap produk terlaris
                $maxQuantity = $bestSellingProducts->max('total_quantity');
                $percentage = $maxQuantity > 0 ? ($product->total_quantity / $maxQuantity) * 100 : 0;
            @endphp
            
            <div class="flex items-center space-x-4">
                <!-- Ranking Badge -->
                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center 
                    {{ $index == 0 ? 'bg-yellow-100 text-yellow-800' : 
                       ($index == 1 ? 'bg-gray-200 text-gray-800' : 
                       ($index == 2 ? 'bg-amber-100 text-amber-800' : 'bg-zanov-orange/10 text-zanov-orange')) }}">
                    <span class="font-bold text-sm">#{{ $index + 1 }}</span>
                </div>
                
                <!-- Product Info -->
                <div class="flex-shrink-0">
                    @if($product->photo)
                        <img src="{{ asset('storage/' . $product->photo) }}" 
                             alt="{{ $product->name }}"
                             class="w-12 h-12 rounded-lg object-cover border border-zanov-orange/20">
                    @else
                        <div class="w-12 h-12 rounded-lg bg-zanov-orange/10 flex items-center justify-center">
                            <i data-feather="package" class="text-zanov-orange"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Product Details -->
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-zanov-dark truncate">{{ $product->name }}</p>
                    <p class="text-sm text-zanov-dark/60">{{ $product->total_quantity }} unit terjual</p>
                </div>
                
                <!-- Progress Bar
                <div class="flex-1 max-w-xs">
                    <div class="h-2 bg-zanov-orange/10 rounded-full overflow-hidden">
                        <div class="h-full bg-zanov-orange rounded-full" 
                             style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
                
                Percentage
                <div class="flex-shrink-0">
                    <span class="font-semibold text-zanov-orange">{{ round($percentage) }}%</span>
                </div> -->
            </div>
            
            @if(!$loop->last)
                <hr class="border-zanov-orange/10">
            @endif
        @empty
            <div class="text-center py-8">
                <div class="w-16 h-16 mx-auto rounded-full bg-zanov-orange/10 flex items-center justify-center mb-4">
                    <i data-feather="shopping-cart" class="text-zanov-orange"></i>
                </div>
                <p class="text-zanov-dark/60">Belum ada data penjualan</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Pendapatan Bulanan -->
<div class="bg-white p-6 rounded-lg shadow-sm border border-zanov-orange/20 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-zanov-dark">Tren Pendapatan</h2>
        <span class="text-sm text-zanov-dark/60">6 Bulan Terakhir</span>
    </div>
    <div>
        {!! $revenueChart->container() !!}
    </div>
</div>

<!-- Quick Stats -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow-sm border border-zanov-orange/20">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-zanov-dark/60">Total Produk</p>
                <p class="mt-1 text-2xl font-semibold text-zanov-orange">{{ App\Models\Product::count() }}</p>
            </div>
            <div class="p-3 rounded-full bg-zanov-orange/10">
                <i data-feather="package" class="text-zanov-orange"></i>
            </div>
        </div>
        <p class="mt-2 text-xs text-zanov-dark/60">
            @php
                $activeProducts = App\Models\Product::where('is_active', 1)->count();
                $percentageActive = App\Models\Product::count() > 0 ? round(($activeProducts / App\Models\Product::count()) * 100) : 0;
            @endphp
            {{ $percentageActive }}% produk aktif
        </p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-sm border border-zanov-orange/20">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-zanov-dark/60">Transaksi Pending</p>
                <p class="mt-1 text-2xl font-semibold text-zanov-orange">
                    {{ App\Models\Transaction::where('payment_status', 'PENDING')->count() }}
                </p>
            </div>
            <div class="p-3 rounded-full bg-zanov-orange/10">
                <i data-feather="clock" class="text-zanov-orange"></i>
            </div>
        </div>
        <p class="mt-2 text-xs text-zanov-dark/60">Perlu verifikasi</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-sm border border-zanov-orange/20 cursor-pointer hover:bg-zanov-orange/5 transition-colors"
         onclick="window.location.href='{{ route('products.index') }}?filter=out-of-stock'">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-zanov-dark/60">Stok Kosong</p>
                <p class="mt-1 text-2xl font-semibold text-zanov-orange">
                    {{ App\Models\Product::where('stock', 0)->where('is_active', 1)->count() }}
                </p>
            </div>
            <div class="p-3 rounded-full bg-zanov-orange/10">
                <i data-feather="x-circle" class="text-zanov-orange"></i>
            </div>
        </div>
        <p class="mt-2 text-xs text-zanov-dark/60 flex items-center">
            <i data-feather="arrow-right" class="w-3 h-3 mr-1"></i>
            Perlu restock segera
        </p>
    </div>
</div>

@push('scripts')
    <script src="{{ $salesTrendChart->cdn() }}"></script>
    {{ $salesTrendChart->script() }}
    {{ $statusChart->script() }}
    {{ $bestRatingChart->script() }}
    {{ $worstRatingChart->script() }}
    {{ $revenueChart->script() }}
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Feather Icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
            
            // Add click event to all cards with links
            document.querySelectorAll('[onclick]').forEach(card => {
                card.style.cursor = 'pointer';
                card.addEventListener('click', function(e) {
                    if (!e.target.closest('a')) { // Prevent if clicking on a link inside
                        window.location.href = this.getAttribute('onclick').match(/'([^']+)'/)[1];
                    }
                });
            });
        });
    </script>
@endpush
@endsection