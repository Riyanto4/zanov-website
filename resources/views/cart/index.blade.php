@extends('layouts.guest.main')

@section('content')
<section class="py-20 px-4 sm:px-6 lg:px-8 bg-white min-h-screen">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-4 uppercase tracking-tight text-accent">Shopping Cart</h1>
            <p class="text-accent">Review your items and proceed to checkout</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Cart Content -->
            <div class="flex-1">
                @if($cart && $cart->items->count() > 0)
                    <div class="bg-primary border border-gray-800 rounded-none mb-8">
                        <!-- Cart Items -->
                        <div class="divide-y divide-gray-800">
                            @foreach($cart->items as $item)
                                <div class="p-6 flex items-center space-x-6">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0 w-24 h-24 bg-gray-900 border border-gray-800">
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
                                        <p class="text-accent font-bold text-lg">${{ number_format($item->price, 2) }}</p>
                                    </div>

                                    <!-- Quantity Controls -->
                                    <div class="flex items-center space-x-3">
                                        <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" onclick="decreaseQuantity({{ $item->id }})" 
                                                    class="w-8 h-8 border border-gray-600 flex items-center justify-center hover:border-accent transition duration-300">
                                                <i data-feather="minus" class="w-4 h-4"></i>
                                            </button>
                                            <input type="number" 
                                                   id="quantity-{{ $item->id }}"
                                                   name="quantity" 
                                                   value="{{ $item->quantity }}" 
                                                   min="1" 
                                                   max="{{ $item->product->stock }}"
                                                   class="w-16 mx-2 px-2 py-1 border border-gray-600 bg-primary text-accent text-center"
                                                   onchange="updateQuantity({{ $item->id }})">
                                            <button type="button" onclick="increaseQuantity({{ $item->id }})"
                                                    class="w-8 h-8 border border-gray-600 flex items-center justify-center hover:border-accent transition duration-300">
                                                <i data-feather="plus" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-accent mb-2">${{ number_format($item->subtotal, 2) }}</p>
                                        
                                        <!-- Remove Button -->
                                        <form action="{{ route('cart.destroy', $item) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-400 hover:text-red-300 text-sm uppercase tracking-wide transition duration-300">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Cart Summary -->
                        <div class="border-t border-gray-800 p-6">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-xl text-gray-300">Total Amount:</span>
                                <span class="text-3xl font-bold text-accent">${{ number_format($cart->total_amount, 2) }}</span>
                            </div>
                            
                            <div class="flex justify-between space-x-4">
                                <a href="{{ route('catalogue') }}" 
                                   class="flex-1 border border-accent text-accent px-6 py-3 text-center uppercase tracking-wider hover:bg-accent hover:text-primary transition duration-300">
                                    Continue Shopping
                                </a>
                                <a href="{{ route('checkout') }}" 
                                class="flex-1 bg-accent text-primary px-6 py-3 uppercase tracking-wider font-bold hover:bg-gray-200 transition duration-300 text-center">
                                    Proceed to Checkout
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Empty Cart -->
                    <div class="text-center py-20 bg-primary border border-gray-800">
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

            <!-- Best Selling Products Sidebar -->
            @if($bestSellingProducts->count() > 0)
            <div class="lg:w-80">
                <div class="bg-primary border border-gray-800 p-6 sticky top-4">
                    <!-- Header -->
                    <div class="text-center mb-6">
                        <div class="w-12 h-12 bg-accent rounded-full flex items-center justify-center mx-auto mb-3">
                            <i data-feather="award" class="w-6 h-6 text-primary"></i>
                        </div>
                        <h3 class="text-xl font-bold text-accent uppercase tracking-tight">Produk Terlaris</h3>
                        <p class="text-gray-400 text-sm mt-1">Most loved by our community</p>
                    </div>

                    <!-- Products List -->
                    <div class="space-y-4">
                        @foreach($bestSellingProducts as $product)
                        <div class="group border border-gray-800 hover:border-accent transition duration-300 p-4">
                            <div class="flex space-x-4">
                                <!-- Product Image -->
                                <div class="flex-shrink-0 w-16 h-16 bg-gray-900 border border-gray-700 group-hover:border-accent transition duration-300">
                                    @if($product->photo)
                                        <img src="{{ Storage::url($product->photo) }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i data-feather="image" class="w-5 h-5 text-gray-600"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-accent group-hover:text-white transition duration-300 text-sm leading-tight mb-1">
                                        {{ $product->name }}
                                    </h4>
                                    <p class="text-accent font-bold text-lg">${{ number_format($product->price, 2) }}</p>
                                    
                                    <!-- Stock & Popularity Badge -->
                                    <div class="flex items-center space-x-2 mt-1">
                                        @if($product->stock > 0)
                                            <span class="text-green-400 text-xs">✓ In Stock</span>
                                        @else
                                            <span class="text-red-400 text-xs">✗ Out of Stock</span>
                                        @endif
                                        
                                        <!-- Popularity Indicator -->
                                        <div class="flex items-center text-yellow-400">
                                            <i data-feather="star" class="w-3 h-3 fill-current"></i>
                                            <span class="text-xs ml-1">Bestseller</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add to Cart Button -->
                            @if($product->stock > 0)
                            <form action="{{ route('cart.store') }}" method="POST" class="mt-3">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" 
                                        class="w-full border border-accent text-accent text-sm py-2 uppercase tracking-wider hover:bg-accent hover:text-primary transition duration-300 flex items-center justify-center space-x-2">
                                    <i data-feather="shopping-cart" class="w-4 h-4"></i>
                                    <span>Add to Cart</span>
                                </button>
                            </form>
                            @else
                            <button disabled 
                                    class="w-full border border-gray-600 text-gray-600 text-sm py-2 uppercase tracking-wider cursor-not-allowed flex items-center justify-center space-x-2 mt-3">
                                <i data-feather="x" class="w-4 h-4"></i>
                                <span>Out of Stock</span>
                            </button>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Call to Action -->
                    <div class="mt-6 pt-6 border-t border-gray-800 text-center">
                        <p class="text-gray-400 text-sm mb-3">Join thousands of satisfied customers</p>
                        <a href="{{ route('catalogue') }}" 
                           class="inline-block border border-accent text-accent px-6 py-2 text-sm uppercase tracking-wider hover:bg-accent hover:text-primary transition duration-300">
                            Explore All Bestsellers
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<script>
function increaseQuantity(itemId) {
    const input = document.getElementById('quantity-' + itemId);
    const max = parseInt(input.max);
    if (input.value < max) {
        input.value = parseInt(input.value) + 1;
        updateQuantity(itemId);
    }
}

function decreaseQuantity(itemId) {
    const input = document.getElementById('quantity-' + itemId);
    if (input.value > 1) {
        input.value = parseInt(input.value) - 1;
        updateQuantity(itemId);
    }
}

function updateQuantity(itemId) {
    const input = document.getElementById('quantity-' + itemId);
    const form = input.closest('form');
    form.submit();
}
</script>
@endsection