<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZANOV | Premium Footwear</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
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
</head>
<body class="bg-primary text-accent font-sans">
    @include('layouts.guest.navbar')

    @yield('content')

    <!-- Footer -->
    <footer class="bg-secondary py-12 border-t border-gray-800">
        <!-- Footer content... -->
    </footer>

    <script>
        // Inisialisasi Feather Icons
        feather.replace();

        // Fungsi dropdown profile
        const profileButton = document.getElementById('profile-button');
        const profileDropdown = document.getElementById('profile-dropdown');

        if (profileButton && profileDropdown) {
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
        }
    </script>
</body>
</html>