<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | ZANOV | Premium Footwear</title>
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
        .spinner {
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 2px solid #ffffff;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 8px;
            vertical-align: middle;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
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
        
        .checkbox {
            border-radius: 0;
        }
        
        .checkbox:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-primary text-accent font-sans">

    <!-- Register Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-secondary min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold mb-4 uppercase tracking-tight">Create Account</h1>
                <p class="text-gray-300">
                    Join ZANOV for premium footwear experience
                </p>
            </div>

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" id="registerForm" class="bg-primary border border-gray-800 p-8">
                @csrf

                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-bold mb-2 uppercase tracking-wide">Name</label>
                    <input id="name" 
                           type="text" 
                           name="name" 
                           value="{{ old('name') }}"
                           required 
                           autofocus 
                           autocomplete="name"
                           class="input-field w-full px-4 py-3 text-primary">
                    @if ($errors->has('name'))
                        <div class="mt-2 text-sm text-red-400">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>

                <!-- Email Address -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-bold mb-2 uppercase tracking-wide">Email</label>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           required 
                           autocomplete="username"
                           class="input-field w-full px-4 py-3 text-primary">
                    @if ($errors->has('email'))
                        <div class="mt-2 text-sm text-red-400">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>

                <!-- Phone Number -->
                <div class="mb-6">
                    <label for="phone" class="block text-sm font-bold mb-2 uppercase tracking-wide">Phone Number</label>
                    <input id="phone" 
                           type="tel" 
                           name="phone" 
                           value="{{ old('phone') }}"
                           required 
                           autocomplete="tel"
                           class="input-field w-full px-4 py-3 text-primary">
                    @if ($errors->has('phone'))
                        <div class="mt-2 text-sm text-red-400">
                            {{ $errors->first('phone') }}
                        </div>
                    @endif
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-bold mb-2 uppercase tracking-wide">Password</label>
                    <input id="password" 
                           type="password" 
                           name="password" 
                           required 
                           autocomplete="new-password"
                           class="input-field w-full px-4 py-3 text-primary">
                    @if ($errors->has('password'))
                        <div class="mt-2 text-sm text-red-400">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-bold mb-2 uppercase tracking-wide">Confirm Password</label>
                    <input id="password_confirmation" 
                           type="password" 
                           name="password_confirmation" 
                           required 
                           autocomplete="new-password"
                           class="input-field w-full px-4 py-3 text-primary">
                    @if ($errors->has('password_confirmation'))
                        <div class="mt-2 text-sm text-red-400">
                            {{ $errors->first('password_confirmation') }}
                        </div>
                    @endif
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between mt-8">
                    <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-accent transition duration-300 underline">
                        Already registered?
                    </a>
                    
                    <button type="submit" 
                            id="registerButton"
                            class="bg-accent text-primary px-6 py-3 font-bold uppercase tracking-wider text-sm hover:bg-gray-200 transition duration-300 flex items-center">
                        <div class="spinner hidden"></div>
                        <span class="button-text">Register</span>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <script>
        // Inisialisasi Feather Icons
        feather.replace();

        // Show spinner on form submit
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const registerButton = document.getElementById('registerButton');
            const spinner = registerButton.querySelector('.spinner');
            const buttonText = registerButton.querySelector('.button-text');
            
            // Show loading spinner
            spinner.classList.remove('hidden');
            buttonText.textContent = 'Creating Account...';
            registerButton.disabled = true;
        });
    </script>
</body>
</html>