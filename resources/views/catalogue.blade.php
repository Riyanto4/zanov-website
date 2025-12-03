@extends('layouts.guest.main')

@section('content')

<!-- Catalogue Section -->
<section class="py-20 px-4 sm:px-6 lg:px-8 bg-white min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 uppercase tracking-tight">Product Catalogue</h1>
            <p class="text-xl text-accent max-w-2xl mx-auto">
                Discover our premium collection of footwear crafted for excellence
            </p>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-accent border border-gray-800 rounded-none p-6 mb-12">
            <div class="flex flex-col md:flex-row gap-6 justify-between items-center">
                <!-- Search Bar -->
                <div class="flex-1 w-full md:w-auto">
                    <form method="GET" action="{{ route('catalogue') }}" class="relative">
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Search products by name, code, or description..." 
                                   class="w-full bg-white border border-gray-700 text-accent placeholder-gray-500 rounded-none px-4 py-3 pl-12 focus:outline-none focus:border-accent transition duration-300">
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
                    <span class="text-white font-medium">Filter by:</span>
                    <div class="flex gap-2">
                        <form method="GET" action="{{ route('catalogue') }}" id="genderFilterForm">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <div class="flex flex-wrap gap-2">
                                <button type="submit" name="gender" value="all"
                                        class="px-4 py-2 text-white border text-sm font-semibold uppercase tracking-wide transition duration-300
                                               {{ (request('gender') == 'all' || !request('gender')) ? 'bg-accent text-primary border-accent' : 'border-gray-700 text-accent hover:border-gray-600 hover:text-primary' }}">
                                    All Products
                                </button>
                                <button type="submit" name="gender" value="MALE"
                                        class="px-4 py-2 border border-blue-500 text-sm font-semibold uppercase tracking-wide transition duration-300
                                               {{ request('gender') == 'MALE' ? 'bg-blue-500/20 text-blue-400' : 'text-blue-500 hover:bg-blue-500/10' }}">
                                    Men
                                </button>
                                <button type="submit" name="gender" value="FEMALE"
                                        class="px-4 py-2 border border-pink-500 text-sm font-semibold uppercase tracking-wide transition duration-300
                                               {{ request('gender') == 'FEMALE' ? 'bg-pink-500/20 text-pink-400' : 'text-pink-500 hover:bg-pink-500/10' }}">
                                    Women
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
                    <span class="text-white">Active filters:</span>
                    
                    @if(request('search'))
                    <div class="flex items-center text-white gap-2 bg-gray-900 border border-gray-700 px-3 py-1.5">
                        <span class="text-sm text-white">Search: "{{ request('search') }}"</span>
                        <a href="{{ route('catalogue', array_merge(request()->except('search'), ['gender' => request('gender')])) }}" 
                           class="text-gray-500 hover:text-white transition duration-300">
                            <i data-feather="x" class="w-4 h-4"></i>
                        </a>
                    </div>
                    @endif
                    
                    @if(request('gender') && request('gender') != 'all')
                    <div class="flex items-center text-white gap-2 bg-gray-900 border border-gray-700 px-3 py-1.5">
                        <span class="text-sm text-white">Gender: {{ ucfirst(strtolower(request('gender'))) }}</span>
                        <a href="{{ route('catalogue', array_merge(request()->except('gender'))) }}" 
                           class="text-gray-500 hover:text-white transition duration-300">
                            <i data-feather="x" class="w-4 h-4"></i>
                        </a>
                    </div>
                    @endif
                    
                    @if(request('search') || (request('gender') && request('gender') != 'all'))
                    <a href="{{ route('catalogue') }}" 
                       class="text-sm text-white hover:text-accent transition duration-300 flex items-center gap-1 ml-2">
                        <i data-feather="trash-2" class="w-4 h-4"></i>
                        Clear all filters
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Products Count -->
        <div class="mb-8">
            <p class="text-accent">
                Showing <span class="font-bold text-accent">{{ $products->total() }}</span> products
                @if(request('search'))
                for "<span class="font-bold text-accent">{{ request('search') }}</span>"
                @endif
                @if(request('gender') && request('gender') != 'all')
                in <span class="font-bold text-accent">{{ ucfirst(strtolower(request('gender'))) }}</span>
                @endif
            </p>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16" id="productsGrid">
            @forelse ($products as $product)
                @php
                    $averageRating = $product->averageRating();
                    $ratingCount = $product->ratings_count;
                @endphp
                
                <div class="bg-primary border border-gray-800 rounded-none overflow-hidden group hover:border-gray-600 transition-all duration-300">
                    <!-- Product Image -->
                    <div class="relative h-80 overflow-hidden">
                        @if($product->photo)
                            <img src="{{ Storage::url($product->photo) }}" 
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
                                {{ $product->gender }}
                            </span>
                        </div>
                        
                        <!-- Stock Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-none border 
                                {{ $product->stock > 0 ? 'border-green-500 text-green-500' : 'border-red-500 text-red-500' }}">
                                {{ $product->stock > 0 ? 'IN STOCK' : 'OUT OF STOCK' }}
                            </span>
                        </div>

                        <!-- Overlay on Hover -->
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/50 transition-all duration-300 flex items-center justify-center">
                            <button class="opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 bg-accent text-primary px-6 py-3 font-bold uppercase tracking-wider text-sm hover:bg-gray-200">
                                Quick View
                            </button>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-xl font-bold text-accent">{{ $product->name }}</h3>
                            <span class="text-2xl font-bold text-accent">{{ $product->price }}</span>
                        </div>
                        
                        <p class="text-sm text-accent mb-2 uppercase tracking-wide">Code: {{ $product->code }}</p>
                        
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
                            <span class="text-sm text-accent">
                                @if($ratingCount > 0)
                                    <span class="font-semibold text-white">{{ number_format($averageRating, 1) }}</span>
                                    <span class="text-gray-500 ml-1">({{ $ratingCount }} {{ Str::plural('review', $ratingCount) }})</span>
                                @else
                                    <span class="text-gray-500">No ratings yet</span>
                                @endif
                            </span>
                        </div>
                        
                        <p class="text-accent mb-6 line-clamp-2 leading-relaxed">
                            {{ Str::limit($product->description, 120) }}
                        </p>
                        
                        <div class="flex justify-between items-center pt-4 border-t border-gray-800">
                           <span class="text-sm text-primary uppercase tracking-wide border bg-accent border-black px-6 py-2 inline-block">
                                Stock:
                                <span class="font-semibold {{ $product->stock > 0 ? 'text-green-400' : 'text-red-400' }}">
                                    {{ $product->stock }}
                                </span>
                            </span>

                            
                            <!-- Add to Cart Button -->
                            <form action="{{ route('cart.store') }}" method="POST" class="flex-shrink-0">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" 
                                        class="flex items-center space-x-2 bg-accent text-primary px-6 py-2 font-bold uppercase tracking-wider text-sm hover:bg-gray-200 transition duration-300 border border-accent
                                            {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed bg-gray-500 text-accent border-gray-500' : '' }}"
                                        {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    <i data-feather="shopping-cart" class="w-4 h-4"></i>
                                    <span>{{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-20">
                    <i data-feather="package" class="w-24 h-24 text-gray-600 mx-auto mb-6"></i>
                    <h3 class="text-3xl font-bold text-accent mb-4 uppercase">No Products Found</h3>
                    <p class="text-gray-500 text-lg">
                        @if(request('search'))
                            No products found for "{{ request('search') }}"
                        @elseif(request('gender') && request('gender') != 'all')
                            No {{ strtolower(request('gender')) }} products available
                        @else
                            We're currently updating our collection. Please check back later.
                        @endif
                    </p>
                    @if(request('search') || request('gender'))
                    <a href="{{ route('catalogue') }}" 
                       class="mt-6 inline-block bg-accent text-primary px-6 py-3 font-bold uppercase tracking-wider text-sm hover:bg-gray-200 transition duration-300">
                        View All Products
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