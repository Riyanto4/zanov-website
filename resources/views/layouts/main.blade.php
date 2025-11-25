<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyZANOV | Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        zanov: {
                            orange: '#E86C00',
                            light: '#FF8C42',
                            white: '#FFFFFF',
                            gray: '#F5F5F5',
                            dark: '#1A1A1A'
                        }
                    },
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="font-poppins bg-zanov-gray">
    <div class="flex h-screen overflow-hidden">
        @include('layouts.sidebar')
        
        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            @include('layouts.navbar')
            
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')  
            </main>
            
</body>
</html>