<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ZANOV | Premium Footwear</title>
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
    </style>
</head>
<body class="bg-primary text-accent font-sans">

    <!-- Login Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-secondary min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold mb-4 uppercase tracking-tight">Welcome Back</h1>
                <p class="text-gray-300">
                    Sign in to your ZANOV account
                </p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" id="loginForm" class="bg-primary border border-gray-800 p-8">
                @csrf

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-900/30 border border-green-800 text-green-300">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Email Address -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-bold mb-2 uppercase tracking-wide">Email</label>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           required 
                           autofocus 
                           autocomplete="username"
                           class="input-field w-full px-4 py-3 text-primary">
                    @if ($errors->has('email'))
                        <div class="mt-2 text-sm text-red-400">
                            {{ $errors->first('email') }}
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
                           autocomplete="current-password"
                           class="input-field w-full px-4 py-3 text-primary">
                    @if ($errors->has('password'))
                        <div class="mt-2 text-sm text-red-400">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>

                <!-- Remember Me -->
                <div class="flex items-center mb-6">
                    <input id="remember_me" 
                           type="checkbox" 
                           name="remember"
                           class="checkbox rounded border-gray-700 bg-primary text-accent focus:ring-accent">
                    <label for="remember_me" class="ms-2 text-sm text-gray-300">
                        Remember me
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-gray-400 hover:text-accent transition duration-300 underline">
                            Forgot your password?
                        </a>
                    @endif
                    
                    <button type="submit" 
                            id="loginButton"
                            class="bg-accent text-primary px-6 py-3 font-bold uppercase tracking-wider text-sm hover:bg-gray-200 transition duration-300 flex items-center">
                        <div class="spinner hidden"></div>
                        <span class="button-text">Log in</span>
                    </button>
                </div>
            </form>

            <!-- Register Link -->
            <div class="text-center mt-8">
                <p class="text-gray-400">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-accent font-bold hover:underline">Create one here</a>
                </p>
            </div>
        </div>
    </section>

    <script>
        // Inisialisasi Feather Icons
        feather.replace();

        // Show spinner on form submit
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const loginButton = document.getElementById('loginButton');
            const spinner = loginButton.querySelector('.spinner');
            const buttonText = loginButton.querySelector('.button-text');
            
            // Show loading spinner
            spinner.classList.remove('hidden');
            buttonText.textContent = 'Logging in...';
            loginButton.disabled = true;
        });
    </script>
</body>
</html>