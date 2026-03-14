<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LPPM') }} - @yield('title', 'Login')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: {
                            50: '#f0f9ff', 100: '#e0f2fe', 200: '#bae6fd',
                            300: '#7dd3fc', 400: '#38bdf8', 500: '#0ea5e9',
                            600: '#0284c7', 700: '#0369a1', 800: '#075985', 900: '#0c4a6e'
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        .auth-gradient {
            background: linear-gradient(135deg, #0c4a6e 0%, #0284c7 50%, #0ea5e9 100%);
        }
        .blob {
            border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%;
            animation: morph 8s ease-in-out infinite;
        }
        @keyframes morph {
            0%, 100% { border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%; }
            50% { border-radius: 58% 42% 30% 70% / 55% 55% 45% 45%; }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 auth-gradient relative overflow-hidden items-center justify-center">
            <!-- Decorative Elements -->
            <div class="absolute top-20 right-20 w-64 h-64 bg-white/10 blob"></div>
            <div class="absolute bottom-20 left-20 w-48 h-48 bg-white/5 blob" style="animation-delay: -3s;"></div>
            <div class="absolute top-1/3 left-1/4 w-32 h-32 bg-cyan-400/10 rounded-full blur-2xl"></div>
            
            <div class="relative z-10 text-center px-12">
                <div class="mb-8">
                    <div class="w-24 h-24 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-flask text-5xl text-white"></i>
                    </div>
                    <h1 class="text-4xl font-bold text-white mb-4">LPPM</h1>
                    <p class="text-xl text-primary-100">Lembaga Penelitian dan<br>Pengabdian kepada Masyarakat</p>
                </div>
                
                <div class="mt-12 grid grid-cols-3 gap-6 px-8">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white">100+</div>
                        <div class="text-primary-200 text-sm">Penelitian</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white">50+</div>
                        <div class="text-primary-200 text-sm">Pengabdian</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-white">200+</div>
                        <div class="text-primary-200 text-sm">Publikasi</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
