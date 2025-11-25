<header class="flex items-center justify-between h-16 px-6 bg-white border-b border-gray-200">
    <div class="flex items-center">
        <button class="md:hidden mr-4 text-orange-500">
            <i data-feather="menu"></i>
        </button>
        <h1 class="text-lg font-semibold text-gray-800">Dashboard</h1>
    </div>
    <div class="flex items-center space-x-4">
        <button class="p-2 text-gray-700 rounded-full hover:bg-gray-100">
            <i data-feather="bell"></i>
        </button>
        @php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
@endphp

<div class="relative">
    <button id="profile-button" class="flex items-center space-x-2 focus:outline-none">
        <img class="w-8 h-8 rounded-full" 
             src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" 
             alt="User profile">
        <span class="hidden md:inline-block text-sm font-medium">
            {{ $user->name ?? 'Guest' }}
        </span>
        <i data-feather="chevron-down" class="hidden md:inline-block w-4 h-4"></i>
    </button>
    
    <!-- Dropdown Menu -->
    <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 border border-gray-200">
        <div class="px-4 py-2 text-xs text-gray-500 border-b border-gray-100">
            Masuk sebagai <span class="font-medium">{{ $user->role ?? 'User' }}</span>
        </div>
        <div class="border-t border-gray-100"></div>
        <form method="POST" action="{{ route('logout') }}" class="block w-full">
            @csrf
            <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                <i data-feather="log-out" class="w-4 h-4 mr-2"></i>
                Keluar
            </button>
        </form>
    </div>
</div>

    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();
        
        const profileButton = document.getElementById('profile-button');
        const profileDropdown = document.getElementById('profile-dropdown');
        
        profileButton.addEventListener('click', function(e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('hidden');
        });
        
        document.addEventListener('click', function() {
            profileDropdown.classList.add('hidden');
        });
        
        profileDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
</script>