<!-- Header/Navbar -->
    <header class="bg-primary border-b border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="#" class="text-2xl font-bold tracking-tight">ZANOV</a>
                </div>

                <!-- Navigation Links -->
                <nav class="hidden md:flex space-x-8">
                    <a href="/" class="hover:text-gray-300 transition duration-300">Home</a>
                    <a href="#brand-story" class="hover:text-gray-300 transition duration-300">About</a>
                    <a href="/catalogue" class="hover:text-gray-300 transition duration-300">Collections</a>
                    <a href="/contact" class="hover:text-gray-300 transition duration-300">Contact</a>
                </nav>

                <!-- Right Side - Login/Profile -->
                <div class="flex items-center space-x-4">
                    <!-- Cart Icon -->
                    <a href="{{ route('cart.index') }}" class="relative p-2 hover:text-gray-300 transition duration-300">
                        <i data-feather="shopping-bag"></i>
                        @auth
                            @php
                                $cartCount = app(App\Http\Controllers\CartController::class)->getCartCount();
                            @endphp
                            @if($cartCount > 0)
                                <span class="absolute -top-2 -right-2 bg-accent text-primary text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        @endauth
                    </a>
                    
                    <!-- Login/Profile Section -->
                    <div id="auth-section">
                        @auth
                            <!-- Jika sudah login -->
                            <div class="relative">
                                <button id="profile-button" class="flex items-center space-x-2 focus:outline-none hover:text-gray-300 transition duration-300">
                                    @if(auth()->user()->photo)
                                        <img class="w-8 h-8 rounded-full border border-accent object-cover" src="{{ asset('storage/' . auth()->user()->photo) }}" alt="User profile">
                                    @else
                                        <div class="w-8 h-8 rounded-full border border-accent bg-gray-800 flex items-center justify-center">
                                            <i data-feather="user" class="w-4 h-4"></i>
                                        </div>
                                    @endif
                                    <span class="hidden md:inline-block text-sm font-medium">Hai, {{ auth()->user()->name }}</span>
                                    <i data-feather="chevron-down" class="hidden md:inline-block w-4 h-4"></i>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-secondary rounded-md shadow-lg py-1 z-10 border border-gray-700">
                                    <div class="px-4 py-2 text-xs text-gray-400 border-b border-gray-700">
                                        Masuk sebagai <span class="font-medium text-white">{{ auth()->user()->name }}</span>
                                    </div>
                                    <a href="/transactions" class="flex items-center px-4 py-2 text-sm text-white hover:bg-gray-800 transition duration-300">
                                        <i data-feather="shopping-bag" class="w-4 h-4 mr-2"></i>
                                        Riwayat Pesanan
                                    </a>
                                    <a href="{{ route('profile.edit.page') }}" class="flex items-center px-4 py-2 text-sm text-white hover:bg-gray-800 transition duration-300">
                                        <i data-feather="settings" class="w-4 h-4 mr-2"></i>
                                        Pengaturan
                                    </a>
                                    <div class="border-t border-gray-700"></div>
                                    <form method="POST" action="{{ route('logout') }}" class="block w-full">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-800 transition duration-300">
                                            <i data-feather="log-out" class="w-4 h-4 mr-2"></i>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <!-- Jika belum login -->
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('login') }}" class="border border-accent text-accent px-4 py-2 hover:bg-accent hover:text-primary transition duration-300 uppercase text-sm tracking-wider">
                                    Login
                                </a>
                            </div>
                        @endauth
                    </div>

                    <!-- Mobile menu button -->
                    <button class="md:hidden p-2 text-white">
                        <i data-feather="menu"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <script>
        // Smooth scroll untuk link dengan hash
document.addEventListener('DOMContentLoaded', function() {
    // Handle click pada link About di navbar
    const aboutLink = document.querySelector('a[href="#brand-story"]');
    if (aboutLink) {
        aboutLink.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetSection = document.getElementById('brand-story');
            if (targetSection) {
                targetSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    }

    // Handle URL dengan hash (jika user langsung buka URL dengan #brand-story)
    if (window.location.hash === '#brand-story') {
        const targetSection = document.getElementById('brand-story');
        if (targetSection) {
            setTimeout(() => {
                targetSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }, 100);
        }
    }
});
    </script>