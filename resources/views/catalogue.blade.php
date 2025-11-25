@extends('layouts.guest.main')


@section('content')

<!-- Catalogue Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-secondary min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-bold mb-6 uppercase tracking-tight">Product Catalogue</h1>
                <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                    Discover our premium collection of footwear crafted for excellence
                </p>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                @forelse ($products as $product)
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
                            
                            <p class="text-sm text-gray-400 mb-2 uppercase tracking-wide">Code: {{ $product->code }}</p>
                            
                            <p class="text-gray-300 mb-6 line-clamp-2 leading-relaxed">
                                {{ Str::limit($product->description, 120) }}
                            </p>
                            
                            <div class="flex justify-between items-center pt-4 border-t border-gray-800">
                                <span class="text-sm text-gray-400 uppercase tracking-wide">
                                    Stock: <span class="font-semibold {{ $product->stock > 0 ? 'text-green-400' : 'text-red-400' }}">
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
                                        <span>{{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-20">
                        <i data-feather="package" class="w-24 h-24 text-gray-600 mx-auto mb-6"></i>
                        <h3 class="text-3xl font-bold text-gray-400 mb-4 uppercase">No Products Available</h3>
                        <p class="text-gray-500 text-lg">We're currently updating our collection. Please check back later.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="flex justify-center">
                    <div class="bg-primary border border-gray-800 rounded-none px-6 py-4">
                        {{ $products->links() }}
                    </div>
                </div>
            @endif
        </div>
    </section>

    @endsection