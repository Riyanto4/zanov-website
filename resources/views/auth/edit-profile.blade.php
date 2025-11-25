<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | ZANOV | Premium Footwear</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#000000',
                        secondary: '#1a1a1a',
                        accent: '#ffffff',
                    }
                }
            }
        }
    </script>
    <style>
        .input-field {
            background-color: #ffffff;
            border: 1px solid #d1d5db;
            border-radius: 0;
            transition: all 0.3s;
        }
        
        .input-field:focus {
            outline: none;
            border-color: #ffffff;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.2);
        }

        .profile-photo-preview {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 3px solid #ffffff;
        }
    </style>
</head>
<body class="bg-primary text-accent font-sans">

    @include('layouts.guest.navbar')

    <!-- Edit Profile Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-secondary min-h-screen">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-12">
                <h1 class="text-4xl font-bold mb-4 uppercase tracking-tight">Edit Profile</h1>
                <p class="text-gray-300">
                    Kelola informasi profil dan keamanan akun Anda
                </p>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-900 border border-green-700 text-green-100 px-6 py-4 mb-8">
                    <div class="flex items-center">
                        <i data-feather="check-circle" class="w-5 h-5 mr-3"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-primary border border-gray-800 p-6">
                        <h2 class="text-xl font-bold mb-6 uppercase tracking-wide">Menu</h2>
                        <nav class="space-y-2">
                            <a href="#profile-info" class="block px-4 py-3 hover:bg-gray-900 transition duration-300 uppercase text-sm tracking-wide">
                                Informasi Profil
                            </a>
                            <a href="#change-password" class="block px-4 py-3 hover:bg-gray-900 transition duration-300 uppercase text-sm tracking-wide">
                                Ubah Password
                            </a>
                            <a href="#profile-photo" class="block px-4 py-3 hover:bg-gray-900 transition duration-300 uppercase text-sm tracking-wide">
                                Foto Profil
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Profile Information Form -->
                    <div id="profile-info" class="bg-primary border border-gray-800 p-8">
                        <h2 class="text-2xl font-bold mb-6 uppercase tracking-wide">Informasi Profil</h2>
                        
                        <form method="POST" action="{{ route('profile.info.update') }}">
                            @csrf
                            @method('PUT')

                            <!-- Name -->
                            <div class="mb-6">
                                <label for="name" class="block text-sm font-bold mb-2 uppercase tracking-wide">Nama</label>
                                <input id="name" 
                                       type="text" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}"
                                       required 
                                       class="input-field w-full px-4 py-3 text-primary">
                                @error('name')
                                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-6">
                                <label for="email" class="block text-sm font-bold mb-2 uppercase tracking-wide">Email</label>
                                <input id="email" 
                                       type="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}"
                                       required 
                                       class="input-field w-full px-4 py-3 text-primary">
                                @error('email')
                                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="mb-6">
                                <label for="phone" class="block text-sm font-bold mb-2 uppercase tracking-wide">No. HP</label>
                                <input id="phone" 
                                       type="tel" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone) }}"
                                       required 
                                       class="input-field w-full px-4 py-3 text-primary">
                                @error('phone')
                                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="bg-accent text-primary px-8 py-3 font-bold uppercase tracking-wider text-sm hover:bg-gray-200 transition duration-300">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Change Password Form -->
                    <div id="change-password" class="bg-primary border border-gray-800 p-8">
                        <h2 class="text-2xl font-bold mb-6 uppercase tracking-wide">Ubah Password</h2>
                        
                        <form method="POST" action="{{ route('profile.password.update') }}">
                            @csrf
                            @method('PUT')

                            <!-- Current Password -->
                            <div class="mb-6">
                                <label for="current_password" class="block text-sm font-bold mb-2 uppercase tracking-wide">Password Saat Ini</label>
                                <input id="current_password" 
                                       type="password" 
                                       name="current_password" 
                                       required 
                                       class="input-field w-full px-4 py-3 text-primary">
                                @error('current_password')
                                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div class="mb-6">
                                <label for="password" class="block text-sm font-bold mb-2 uppercase tracking-wide">Password Baru</label>
                                <input id="password" 
                                       type="password" 
                                       name="password" 
                                       required 
                                       class="input-field w-full px-4 py-3 text-primary">
                                @error('password')
                                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-6">
                                <label for="password_confirmation" class="block text-sm font-bold mb-2 uppercase tracking-wide">Konfirmasi Password Baru</label>
                                <input id="password_confirmation" 
                                       type="password" 
                                       name="password_confirmation" 
                                       required 
                                       class="input-field w-full px-4 py-3 text-primary">
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="bg-accent text-primary px-8 py-3 font-bold uppercase tracking-wider text-sm hover:bg-gray-200 transition duration-300">
                                    Ubah Password
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Profile Photo Form -->
                    <div id="profile-photo" class="bg-primary border border-gray-800 p-8">
                        <h2 class="text-2xl font-bold mb-6 uppercase tracking-wide">Foto Profil</h2>
                        
                        <!-- Current Photo -->
                        <div class="mb-6 text-center">
                            @if($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}" 
                                     alt="Profile Photo" 
                                     class="profile-photo-preview mx-auto mb-4"
                                     id="currentPhoto">
                            @else
                                <div class="profile-photo-preview mx-auto mb-4 bg-gray-800 flex items-center justify-center">
                                    <i data-feather="user" class="w-16 h-16 text-gray-600"></i>
                                </div>
                            @endif
                            <p class="text-sm text-gray-400">Foto profil saat ini</p>
                        </div>

                        <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Photo Upload -->
                            <div class="mb-6">
                                <label for="photo" class="block text-sm font-bold mb-2 uppercase tracking-wide">Upload Foto Baru</label>
                                <input id="photo" 
                                       type="file" 
                                       name="photo" 
                                       accept="image/*"
                                       required 
                                       class="input-field w-full px-4 py-3 text-primary"
                                       onchange="previewPhoto(event)">
                                <p class="mt-2 text-xs text-gray-400">Format: JPG, PNG, GIF. Maksimal 2MB</p>
                                @error('photo')
                                    <div class="mt-2 text-sm text-red-400">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Photo Preview -->
                            <div class="mb-6 text-center hidden" id="photoPreviewContainer">
                                <p class="text-sm font-bold mb-2 uppercase tracking-wide">Preview:</p>
                                <img id="photoPreview" 
                                     class="profile-photo-preview mx-auto" 
                                     alt="Photo Preview">
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="bg-accent text-primary px-8 py-3 font-bold uppercase tracking-wider text-sm hover:bg-gray-200 transition duration-300">
                                    Upload Foto
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>


    <script>
        // Initialize Feather Icons
        feather.replace();

        // Profile dropdown toggle
        const profileButton = document.getElementById('profile-button');
        const profileDropdown = document.getElementById('profile-dropdown');

        if (profileButton && profileDropdown) {
            profileButton.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!profileButton.contains(e.target) && !profileDropdown.contains(e.target)) {
                    profileDropdown.classList.add('hidden');
                }
            });
        }

        // Photo preview function
        function previewPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('photoPreview');
                    const container = document.getElementById('photoPreviewContainer');
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
