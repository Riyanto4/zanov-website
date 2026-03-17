@extends('layouts.guest.main')

@section('content')
<!-- Hero Section -->
<section class="relative h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 bg-black/50 z-10"></div>
    <video autoplay muted loop class="absolute w-full h-full object-cover">
        <source src="http://static.photos/black/1200x630/42" type="video/mp4">
    </video>
    <div class="relative z-20 text-center px-4">
        <h1 class="text-5xl md:text-7xl font-bold mb-6 tracking-tight">ZANOV</h1>
        <p class="text-xl md:text-2xl mb-8 max-w-2xl mx-auto">Elegance in every step</p>
        <a href="/catalogue" class="bg-accent text-primary px-8 py-3 inline-block rounded-none uppercase font-bold tracking-wider hover:bg-gray-200 transition duration-300">
            Jelajahi Koleksi
        </a>
    </div>
</section>

<!-- Featured Products -->
<section id="products" class="py-20 px-4 sm:px-6 lg:px-8 bg-secondary">
    <div class="max-w-7xl mx-auto">
        @php
            $bestSellingProducts = \App\Models\Product::where('is_active', 1)
                ->withSum('transactionItems as total_sold', 'quantity')
                ->orderByDesc('total_sold')
                ->limit(6)
                ->get();

            $latestProducts = \App\Models\Product::where('is_active', 1)
                ->orderByDesc('created_at')
                ->limit(6)
                ->get();

            $resolveProductImageUrl = function ($product) {
                $photo = $product->photo;
                if (!$photo) {
                    return 'https://picsum.photos/seed/product-' . $product->id . '/600/600';
                }
                if (\Illuminate\Support\Str::startsWith($photo, ['http://', 'https://'])) {
                    return $photo;
                }
                if (\Illuminate\Support\Str::startsWith($photo, 'storage/')) {
                    return asset($photo);
                }
                return asset('storage/' . $photo);
            };
        @endphp

        <h2 class="text-3xl font-bold mb-12 text-center uppercase">Produk Terlaris</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($bestSellingProducts as $product)
            <div class="group bg-white shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="relative overflow-hidden">
                    <img src="{{ $resolveProductImageUrl($product) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-80 object-cover group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition duration-300"></div>
                    @if($product->stock <= 0)
                    <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 text-sm font-bold">
                        HABIS
                    </div>
                    @endif
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-bold text-black">{{ $product->name }}</h3>
                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded uppercase">
                            {{ $product->gender }}
                        </span>
                    </div>
                    <p class="text-gray-600 mb-4 line-clamp-2">{{ Str::limit($product->description, 100) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-accent">${{ number_format($product->price, 2) }}</span>
                        @if($product->stock > 0)
                        <a href="/catalogue" class="bg-accent text-primary px-6 py-2 uppercase tracking-wide text-sm hover:bg-gray-200 transition duration-300">
                            Lihat Detail
                        </a>
                        @else
                        <button disabled class="bg-gray-400 text-white px-6 py-2 uppercase tracking-wide text-sm cursor-not-allowed">
                            Habis
                        </button>
                        @endif
                    </div>
                    @if($product->stock > 0 && $product->stock <= 5)
                    <div class="mt-3 text-sm text-orange-500 font-medium">
                        Hanya tersisa {{ $product->stock }} stok!
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <h2 class="text-3xl font-bold mt-16 mb-12 text-center uppercase">Produk Terbaru</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($latestProducts as $product)
            <div class="group bg-white shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="relative overflow-hidden">
                    <img src="{{ $resolveProductImageUrl($product) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-80 object-cover group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition duration-300"></div>
                    @if($product->stock <= 0)
                    <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 text-sm font-bold">
                        HABIS
                    </div>
                    @endif
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-bold text-black">{{ $product->name }}</h3>
                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded uppercase">
                            {{ $product->gender }}
                        </span>
                    </div>
                    <p class="text-gray-600 mb-4 line-clamp-2">{{ Str::limit($product->description, 100) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-accent">${{ number_format($product->price, 2) }}</span>
                        @if($product->stock > 0)
                        <a href="/catalogue" class="bg-accent text-primary px-6 py-2 uppercase tracking-wide text-sm hover:bg-gray-200 transition duration-300">
                            Lihat Detail
                        </a>
                        @else
                        <button disabled class="bg-gray-400 text-white px-6 py-2 uppercase tracking-wide text-sm cursor-not-allowed">
                            Habis
                        </button>
                        @endif
                    </div>
                    @if($product->stock > 0 && $product->stock <= 5)
                    <div class="mt-3 text-sm text-orange-500 font-medium">
                        Hanya tersisa {{ $product->stock }} stok!
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="/catalogue" class="bg-accent text-primary px-8 py-3 inline-block rounded-none uppercase font-bold tracking-wider hover:bg-gray-200 transition duration-300">
                Lihat Semua Produk
            </a>
        </div>
    </div>
</section>

<!-- Brand Story -->
<section id="brand-story" class="py-20 bg-primary text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-4xl font-bold mb-6 uppercase">Kisah Kami</h2>
                <p class="text-lg mb-6 leading-relaxed">
                    Sejak November 2022, ZANOV hadir sebagai home industry yang berfokus pada pembuatan alas kaki berkualitas. 
                    Berawal dari semangat sederhana, kami berkomitmen menghadirkan produk yang tidak hanya menunjang penampilan, 
                    tetapi juga memberikan kenyamanan dalam setiap langkah.
                </p>
                <p class="text-lg mb-8 leading-relaxed">
                    Setiap pasang sepatu ZANOV dibuat dengan ketelitian dan menggunakan bahan pilihan untuk memastikan kualitas terbaik. 
                    Dengan menggabungkan proses pengerjaan yang rapi dan perhatian pada detail, kami berusaha menghadirkan produk 
                    yang kuat, nyaman, dan tetap stylish untuk digunakan sehari-hari.
                </p>
                <div class="flex space-x-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-accent mb-2">3+</div>
                        <div class="text-sm uppercase tracking-wide">Tahun Pengalaman</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-accent mb-2">10+</div>
                        <div class="text-sm uppercase tracking-wide">Pelanggan Puas</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-accent mb-2">50+</div>
                        <div class="text-sm uppercase tracking-wide">Desain</div>
                    </div>
                </div>
            </div>
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                     alt="ZANOV Craftsmanship" 
                     class="w-full h-[500px] object-cover shadow-2xl">
                <div class="absolute -bottom-6 -left-6 w-24 h-24 border-4 border-accent"></div>
                <div class="absolute -top-6 -right-6 w-32 h-32 border-4 border-accent"></div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-20 bg-secondary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-4">Kualitas Premium</h3>
                <p class="text-gray-600">Dibuat dengan bahan terbaik dan perhatian terhadap detail</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-4">Belanja Aman</h3>
                <p class="text-gray-600">Proses pembayaran yang aman dan terpercaya</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-4">Garansi Panjang</h3>
                <p class="text-gray-600">Garansi selama 5 bulan dan terjamin jika barang rusak</p>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Brand Column -->
            <div class="col-span-1 md:col-span-2">
                <h3 class="text-2xl font-bold mb-4">ZANOV</h3>
                <p class="text-gray-400 mb-6 max-w-md">
                    Elegance in every step
                </p>
                <div class="flex space-x-4">
                    <a href="https://www.instagram.com/zanovshoes" class="text-gray-400 hover:text-accent transition duration-300">
                        <i data-feather="instagram" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Tautan Cepat</h4>
                <ul class="space-y-2">
                    <li><a href="/" class="text-gray-400 hover:text-accent transition duration-300">Beranda</a></li>
                    <li><a href="#brand-story" class="text-gray-400 hover:text-accent transition duration-300">Tentang Kami</a></li>
                    <li><a href="/catalogue" class="text-gray-400 hover:text-accent transition duration-300">Koleksi</a></li>
                    <li><a href="/contact" class="text-gray-400 hover:text-accent transition duration-300">Kontak</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Hubungi Kami</h4>
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <i data-feather="map-pin" class="w-5 h-5 text-accent mt-0.5"></i>
                        <span class="text-gray-400">Jl. Arsadimeja<br>Purwokerto Selatan</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i data-feather="phone" class="w-5 h-5 text-accent"></i>
                        <span class="text-gray-400">+62 895-3830-27843</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <i data-feather="mail" class="w-5 h-5 text-accent"></i>
                        <span class="text-gray-400">zanovshoes@gmail.com</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-400 text-sm mb-4 md:mb-0">
                &copy; 2024 ZANOV. All rights reserved.
            </p>
            <div class="flex space-x-6 text-sm">
                <a href="#" class="text-gray-400 hover:text-accent transition duration-300">Privacy Policy</a>
                <a href="#" class="text-gray-400 hover:text-accent transition duration-300">Terms of Service</a>
                <a href="#" class="text-gray-400 hover:text-accent transition duration-300">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>
@endsection
