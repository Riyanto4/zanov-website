<!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-zanov-orange text-zanov-white">
                <div class="flex items-center justify-center h-16 px-4 border-b border-zanov-light">
                    <div class="text-2xl font-bold tracking-wider">ZANOV</div>
                </div>
                <div class="flex flex-col flex-grow px-4 py-4 overflow-y-auto">
                   <nav class="flex-1 space-y-2">
                    <a href="{{ route('dashboard') }}" 
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg 
                            {{ Request::is('dashboard') ? 'bg-zanov-light text-zanov-dark' : 'text-zanov-white hover:bg-zanov-light hover:text-zanov-dark transition-colors duration-200' }}">
                        <i class="fa-solid fa-house w-5 h-5 mr-3"></i>
                        Dashboard
                    </a>

                    <a href="{{ route('products.index') }}" 
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg 
                            {{ Request::is('products*') ? 'bg-zanov-light text-zanov-dark' : 'text-zanov-white hover:bg-zanov-light hover:text-zanov-dark transition-colors duration-200' }}">
                        <i class="fa-solid fa-bag-shopping w-5 h-5 mr-3"></i>
                        Produk
                    </a>
                         <a href="{{ route('transactions.all') }}" 
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg 
                            {{ Request::is('admin/transactions*') ? 'bg-zanov-light text-zanov-dark' : 'text-zanov-white hover:bg-zanov-light hover:text-zanov-dark transition-colors duration-200' }}">
                        <i class="fa-solid fa-bag-shopping w-5 h-5 mr-3"></i>
                        Transaksi
                    </a>
                        <a href="{{ route('stock-statement.index') }}" 
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg 
                            {{ Request::is('stock-statement*') ? 'bg-zanov-light text-zanov-dark' : 'text-zanov-white hover:bg-zanov-light hover:text-zanov-dark transition-colors duration-200' }}">
                        <i class="fa-solid fa-bag-shopping w-5 h-5 mr-3"></i>
                        Stock Transactions
                    </a>
                        <a href="{{ route('admin.users.index') }}" 
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-lg 
                            {{ Request::is('stock-statement*') ? 'bg-zanov-light text-zanov-dark' : 'text-zanov-white hover:bg-zanov-light hover:text-zanov-dark transition-colors duration-200' }}">
                        <i class="fa-solid fa-users w-5 h-5 mr-3"></i>
                        Users
                    </a>
                    </nav>

                </div>
                <div class="p-4 border-t border-zanov-light">
                    <div class="flex items-center">
                            <div class="ml-3">
                            <p class="text-sm font-medium">My ZANOV 2025</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>