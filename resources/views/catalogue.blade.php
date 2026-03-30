@extends('layouts.guest.main')

@section('content')

<!-- Catalogue Section -->
<section x-data="{ 
    isOpen: false, 
    selectedImage: '', 
    selectedName: '',
    openModal(image, name) {
        this.selectedImage = image;
        this.selectedName = name;
        this.isOpen = true;
    }
}" 
x-effect="isOpen ? document.body.classList.add('overflow-hidden') : document.body.classList.remove('overflow-hidden')"
class="py-20 px-4 sm:px-6 lg:px-8 bg-secondary min-h-screen">
    <div class="max-w-7xl mx-auto">
        @php
            $genderLabels = [
                'MALE' => 'Pria',
                'FEMALE' => 'Wanita',
                'UNISEX' => 'Unisex',
            ];
            $activeGenderLabel = request('gender') && request('gender') !== 'all'
                ? ($genderLabels[request('gender')] ?? request('gender'))
                : null;
        @endphp

        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 uppercase tracking-tight">Katalog Produk</h1>
            <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                Temukan koleksi alas kaki premium kami yang dibuat dengan standar terbaik
            </p>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-primary border border-gray-800 rounded-none p-6 mb-12">
            <div class="flex flex-col md:flex-row gap-6 justify-between items-center">
                <!-- Search Bar -->
                <div class="flex-1 w-full md:w-auto">
                    <form method="GET" action="{{ route('catalogue') }}" class="relative">
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari produk berdasarkan nama, kode, atau deskripsi..." 
                                   class="w-full bg-gray-900 border border-gray-700 text-accent placeholder-gray-500 rounded-none px-4 py-3 pl-12 focus:outline-none focus:border-accent transition duration-300">
                            <div class="absolute left-4 top-3.5">
                                <i data-feather="search" class="w-5 h-5 text-gray-500"></i>
                            </div>
                            @if(request('search'))
                            <a href="{{ route('catalogue', array_merge(request()->except('search'), ['gender' => request('gender')])) }}" 
                               class="absolute right-4 top-3.5 text-gray-500 hover:text-accent transition duration-300">
                                <i data-feather="x" class="w-5 h-5"></i>
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Gender Filter -->
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <span class="text-gray-300 font-medium">Filter berdasarkan:</span>
                    <div class="flex gap-2">
                        <form method="GET" action="{{ route('catalogue') }}" id="genderFilterForm">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <div class="flex flex-wrap gap-2">
                                <button type="submit" name="gender" value="all"
                                        class="px-4 py-2 border text-sm font-semibold uppercase tracking-wide transition duration-300
                                               {{ (request('gender') == 'all' || !request('gender')) ? 'bg-accent text-primary border-accent' : 'border-gray-700 text-gray-300 hover:border-gray-600 hover:text-accent' }}">
                                    Semua Produk
                                </button>
                                <button type="submit" name="gender" value="MALE"
                                        class="px-4 py-2 border border-blue-500 text-sm font-semibold uppercase tracking-wide transition duration-300
                                               {{ request('gender') == 'MALE' ? 'bg-blue-500/20 text-blue-400' : 'text-blue-500 hover:bg-blue-500/10' }}">
                                    Pria
                                </button>
                                <button type="submit" name="gender" value="FEMALE"
                                        class="px-4 py-2 border border-pink-500 text-sm font-semibold uppercase tracking-wide transition duration-300
                                               {{ request('gender') == 'FEMALE' ? 'bg-pink-500/20 text-pink-400' : 'text-pink-500 hover:bg-pink-500/10' }}">
                                    Wanita
                                </button>
                                <button type="submit" name="gender" value="UNISEX"
                                        class="px-4 py-2 border border-purple-500 text-sm font-semibold uppercase tracking-wide transition duration-300
                                               {{ request('gender') == 'UNISEX' ? 'bg-purple-500/20 text-purple-400' : 'text-purple-500 hover:bg-purple-500/10' }}">
                                    Unisex
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Active Filters Display -->
            @if(request('search') || request('gender'))
            <div class="mt-6 pt-6 border-t border-gray-800">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-gray-400">Filter aktif:</span>
                    
                    @if(request('search'))
                    <div class="flex items-center gap-2 bg-gray-900 border border-gray-700 px-3 py-1.5">
                        <span class="text-sm text-gray-300">Pencarian: "{{ request('search') }}"</span>
                        <a href="{{ route('catalogue', array_merge(request()->except('search'), ['gender' => request('gender')])) }}" 
                           class="text-gray-500 hover:text-accent transition duration-300">
                            <i data-feather="x" class="w-4 h-4"></i>
                        </a>
                    </div>
                    @endif
                    
                    @if(request('gender') && request('gender') != 'all')
                    <div class="flex items-center gap-2 bg-gray-900 border border-gray-700 px-3 py-1.5">
                        <span class="text-sm text-gray-300">Jenis Kelamin: {{ $activeGenderLabel }}</span>
                        <a href="{{ route('catalogue', array_merge(request()->except('gender'))) }}" 
                           class="text-gray-500 hover:text-accent transition duration-300">
                            <i data-feather="x" class="w-4 h-4"></i>
                        </a>
                    </div>
                    @endif
                    
                    @if(request('search') || (request('gender') && request('gender') != 'all'))
                    <a href="{{ route('catalogue') }}" 
                       class="text-sm text-accent hover:text-gray-300 transition duration-300 flex items-center gap-1 ml-2">
                        <i data-feather="trash-2" class="w-4 h-4"></i>
                        Hapus semua filter
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Products Count -->
        <div class="mb-8">
            <p class="text-gray-400">
                Menampilkan <span class="font-bold text-accent">{{ $products->total() }}</span> produk
                @if(request('search'))
                untuk "<span class="font-bold text-accent">{{ request('search') }}</span>"
                @endif
                @if(request('gender') && request('gender') != 'all')
                pada <span class="font-bold text-accent">{{ $activeGenderLabel }}</span>
                @endif
            </p>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16" id="productsGrid">
            @forelse ($products as $product)
                @php
                    $averageRating = $product->averageRating();
                    $ratingCount = $product->ratings_count;
                    $imageUrl = $product->photo ? Storage::url($product->photo) : null;
                @endphp
                
                <div class="bg-primary border border-gray-800 rounded-none overflow-hidden group hover:border-gray-600 transition-all duration-300">
                    <!-- Product Image -->
                    <div class="relative h-80 overflow-hidden">
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gray-900 flex items-center justify-center">
                                <i data-feather="image" class="w-16 h-16 text-gray-600"></i>
                            </div>
                        @endif
                        
                        <!-- Gender Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-none border 
                                {{ $product->gender == 'MALE' ? 'border-blue-500 text-blue-500' : 
                                   ($product->gender == 'FEMALE' ? 'border-pink-500 text-pink-500' : 'border-purple-500 text-purple-500') }}">
                                {{ $genderLabels[$product->gender] ?? $product->gender }}
                            </span>
                        </div>
                        
                        <!-- Stock Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-none border 
                                {{ $product->stock > 0 ? 'border-green-500 text-green-500' : 'border-red-500 text-red-500' }}">
                                {{ $product->stock > 0 ? 'TERSEDIA' : 'HABIS' }}
                            </span>
                        </div>

                        <!-- Overlay on Hover -->
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/50 transition-all duration-300 flex items-center justify-center">
                            @if($imageUrl)
                            <button @click="openModal('{{ $imageUrl }}', '{{ $product->name }}')" 
                                    class="opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 bg-accent text-primary px-6 py-3 font-bold uppercase tracking-wider text-sm hover:bg-gray-200">
                                Lihat Cepat
                            </button>
                            @endif
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-xl font-bold text-accent">{{ $product->name }}</h3>
                            <span class="text-2xl font-bold text-accent">{{ $product->price }}</span>
                        </div>
                        
                        <p class="text-sm text-gray-400 mb-2 uppercase tracking-wide">Kode: {{ $product->code }}</p>
                        
                        <!-- Rating Display -->
                        <div class="flex items-center mb-4">
                            <!-- Star Ratings -->
                            <div class="flex items-center mr-3">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($averageRating >= $i)
                                        <!-- Full Star -->
                                        <i data-feather="star" class="w-4 h-4 fill-current text-yellow-500 text-yellow-500"></i>
                                    @elseif($averageRating > ($i - 0.5) && $averageRating < $i)
                                        <!-- Half Star -->
                                        <div class="relative">
                                            <i data-feather="star" class="w-4 h-4 text-gray-600"></i>
                                            <div class="absolute top-0 left-0 overflow-hidden" style="width: 50%;">
                                                <i data-feather="star" class="w-4 h-4 fill-current text-yellow-500 text-yellow-500"></i>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Empty Star -->
                                        <i data-feather="star" class="w-4 h-4 text-gray-600"></i>
                                    @endif
                                @endfor
                            </div>
                            
                            <!-- Rating Text -->
                            <span class="text-sm text-gray-400">
                                @if($ratingCount > 0)
                                    <span class="font-semibold text-white">{{ number_format($averageRating, 1) }}</span>
                                    <span class="text-gray-500 ml-1">({{ $ratingCount }} ulasan)</span>
                                @else
                                    <span class="text-gray-500">Belum ada ulasan</span>
                                @endif
                            </span>
                        </div>
                        
                        <p class="text-gray-300 mb-6 line-clamp-2 leading-relaxed">
                            {{ Str::limit($product->description, 120) }}
                        </p>
                        
                        <div class="flex justify-between items-center pt-4 border-t border-gray-800">
                            <span class="text-sm text-gray-400 uppercase tracking-wide">
                                Stok: <span class="font-semibold {{ $product->stock > 0 ? 'text-green-400' : 'text-red-400' }}">
                                    {{ $product->stock }}
                                </span>
                            </span>
                            
                            <!-- Add to Cart Button -->
                            <form action="{{ route('cart.store') }}" method="POST" class="flex-shrink-0">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" 
                                        class="flex items-center space-x-2 bg-accent text-primary px-6 py-2 font-bold uppercase tracking-wider text-sm hover:bg-gray-200 transition duration-300 border border-accent
                                            {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed bg-gray-500 text-gray-300 border-gray-500' : '' }}"
                                        {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    <i data-feather="shopping-cart" class="w-4 h-4"></i>
                                    <span>{{ $product->stock > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-20">
                    <i data-feather="package" class="w-24 h-24 text-gray-600 mx-auto mb-6"></i>
                    <h3 class="text-3xl font-bold text-gray-400 mb-4 uppercase">Produk Tidak Ditemukan</h3>
                    <p class="text-gray-500 text-lg">
                        @if(request('search'))
                            Tidak ada produk untuk "{{ request('search') }}"
                        @elseif(request('gender') && request('gender') != 'all')
                            Tidak ada produk {{ $activeGenderLabel }}
                        @else
                            Kami sedang memperbarui koleksi. Silakan cek kembali nanti.
                        @endif
                    </p>
                    @if(request('search') || request('gender'))
                    <a href="{{ route('catalogue') }}" 
                       class="mt-6 inline-block bg-accent text-primary px-6 py-3 font-bold uppercase tracking-wider text-sm hover:bg-gray-200 transition duration-300">
                        Lihat Semua Produk
                    </a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="flex justify-center">
                <div class="bg-primary border border-gray-800 rounded-none px-6 py-4">
                    {{ $products->withQueryString()->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Quick View Modal -->
    <div x-show="isOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" role="dialog" aria-modal="true"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div @click="isOpen = false" 
                 class="fixed inset-0 transition-opacity bg-black bg-opacity-95 backdrop-blur-sm" aria-hidden="true"></div>

            <!-- Modal Panel -->
            <div x-show="isOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block overflow-hidden transition-all transform bg-primary border border-gray-800 rounded-none sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                
                <div class="relative bg-primary">
                    <!-- Close Button -->
                    <button @click="isOpen = false" 
                            class="absolute top-6 right-6 z-20 text-gray-400 hover:text-white transition duration-300 bg-black/50 p-2 border border-gray-800 hover:border-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <div class="flex flex-col md:flex-row min-h-[500px]">
                        <!-- Image Container -->
                        <div class="w-full md:w-3/5 bg-black flex items-center justify-center p-4">
                            <img :src="selectedImage" :alt="selectedName" 
                                 class="max-w-full h-auto object-contain max-h-[85vh] shadow-2xl">
                        </div>
                        
                        <!-- Info Container -->
                        <div class="w-full md:w-2/5 p-12 text-left flex flex-col justify-center border-l border-gray-800">
                            <div class="mb-8">
                                <span class="text-xs font-bold tracking-[0.2em] text-gray-500 uppercase mb-4 block">Product Detail</span>
                                <h2 class="text-4xl font-bold text-accent mb-6 uppercase tracking-tight leading-tight" x-text="selectedName"></h2>
                                <div class="h-1 w-20 bg-accent mb-8"></div>
                                
                            </div>
                            
                            <div class="flex flex-col sm:flex-row gap-4 mt-auto">
                                <button @click="isOpen = false" 
                                        class="flex-1 bg-accent text-primary px-8 py-4 font-bold uppercase tracking-widest text-sm hover:bg-gray-200 transition duration-300 text-center">
                                    Tutup Preview
                                </button>
                                <button @click="isOpen = false" 
                                        class="flex-1 border border-gray-700 text-accent px-8 py-4 font-bold uppercase tracking-widest text-sm hover:border-accent transition duration-300 text-center">
                                    Kembali
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Clear search on 'x' click
    document.querySelectorAll('.clear-search').forEach(button => {
        button.addEventListener('click', function() {
            searchInput.value = '';
            document.querySelector('#genderFilterForm').submit();
        });
    });

    // Initialize Feather icons after page load
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>

<style>
    /* x-cloak for Alpine.js */
    [x-cloak] { display: none !important; }

    /* Custom styling for star ratings */
    .star-rating i {
        stroke-width: 1;
        stroke: currentColor;
    }
    
    .star-rating .filled {
        fill: currentColor;
    }
    
    /* Half star styling */
    .half-star-container {
        position: relative;
        display: inline-block;
    }
    
    .half-star-fill {
        position: absolute;
        top: 0;
        left: 0;
        overflow: hidden;
        width: 50%;
        height: 100%;
    }
</style>
@endpush
