<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Admin {{ $settings['site_name'] ?? 'LPPM' }}</title>
    
    <!-- Favicon -->
    @if($settings['site_favicon'] ?? false)
    <link rel="icon" href="{{ asset('storage/' . $settings['site_favicon']) }}">
    @endif
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">
    <div x-data="{ sidebarOpen: true, mobileMenuOpen: false }" class="min-h-screen">
        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" x-cloak class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden" @click="mobileMenuOpen = false"></div>
        
        <!-- Sidebar -->
        <aside :class="{'translate-x-0': sidebarOpen || mobileMenuOpen, '-translate-x-full': !sidebarOpen && !mobileMenuOpen}"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0">
            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-4 bg-gray-800">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                    @if($settings['site_logo'] ?? false)
                    <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="{{ $settings['site_name'] ?? 'LPPM' }}" class="h-8 w-auto">
                    @else
                    <div class="h-8 w-8 bg-primary-600 rounded flex items-center justify-center">
                        <i class="fas fa-building text-white text-sm"></i>
                    </div>
                    @endif
                    <span class="text-xl font-bold">{{ $settings['site_name'] ?? 'LPPM' }}</span>
                </a>
                <button @click="mobileMenuOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Navigation -->
            <nav class="mt-4 px-2 space-y-1 overflow-y-auto h-[calc(100vh-4rem)]">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-home w-5 mr-3"></i>
                    Dashboard
                </a>
                
                <div class="pt-4 pb-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Konten</div>
                
                <a href="{{ route('admin.pages.index') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.pages.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-file-alt w-5 mr-3"></i>
                    Halaman
                </a>
                
                <a href="{{ route('admin.news.index') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.news.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-newspaper w-5 mr-3"></i>
                    Berita
                </a>
                
                <a href="{{ route('admin.researches.index') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.researches.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-flask w-5 mr-3"></i>
                    Penelitian
                </a>
                
                <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-tags w-5 mr-3"></i>
                    Kategori
                </a>
                
                <a href="{{ route('admin.sliders.index') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.sliders.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-images w-5 mr-3"></i>
                    Slider
                </a>
                
                <div class="pt-4 pb-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Hibah Internal</div>
                
                <a href="{{ route('admin.internal-grants.schemes.index') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.internal-grants.schemes.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-layer-group w-5 mr-3"></i>
                    Skema Hibah
                </a>
                
                <a href="{{ route('admin.internal-grants.periods.index') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.internal-grants.periods.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-calendar-alt w-5 mr-3"></i>
                    Periode Pengajuan
                </a>
                
                <a href="{{ route('admin.internal-grants.submissions.index') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.internal-grants.submissions.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-file-upload w-5 mr-3"></i>
                    Pengajuan
                </a>
                
                <a href="{{ route('admin.internal-grants.grants.index') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.internal-grants.grants.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-file-contract w-5 mr-3"></i>
                    Hibah Aktif
                </a>
                
                <a href="{{ route('admin.internal-grants.reports.index') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.internal-grants.reports.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-chart-bar w-5 mr-3"></i>
                    Laporan BIMA
                </a>
                
                <div class="pt-4 pb-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Pengaturan</div>
                
                <a href="{{ route('admin.menus.index') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.menus.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-bars w-5 mr-3"></i>
                    Menu
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-users w-5 mr-3"></i>
                    Pengguna
                </a>
                
                <a href="{{ route('admin.roles.index') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.roles.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-user-tag w-5 mr-3"></i>
                    Role & Izin
                </a>
                
                <a href="{{ route('admin.languages.index') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.languages.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-globe w-5 mr-3"></i>
                    Bahasa
                </a>
                
                @if(Auth::user()->hasRole('super-admin'))
                <a href="{{ route('admin.backup.index') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.backup.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-database w-5 mr-3"></i>
                    Backup Database
                </a>
                @endif
                
                <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-2.5 text-sm rounded-lg {{ request()->routeIs('admin.settings.*') ? 'bg-primary-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                    <i class="fas fa-cog w-5 mr-3"></i>
                    Pengaturan
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div :class="{'lg:ml-64': sidebarOpen}" class="lg:ml-64 transition-all duration-300">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm sticky top-0 z-30">
                <div class="flex items-center justify-between h-16 px-4">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:block text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                        <button @click="mobileMenuOpen = true" class="lg:hidden text-gray-600 hover:text-gray-900">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Language Switcher -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center text-gray-600 hover:text-gray-900">
                                <i class="fas fa-globe mr-1"></i>
                                <span class="uppercase">{{ app()->getLocale() }}</span>
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            <div x-show="open" x-cloak @click.away="open = false" class="absolute right-0 mt-2 w-32 bg-white rounded-lg shadow-lg border py-1">
                                @foreach($activeLanguages ?? [] as $lang)
                                <a href="{{ route('lang.switch', $lang->code) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ $lang->native_name }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- User Menu -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3b82f6&color=fff" class="w-8 h-8 rounded-full" alt="">
                                <span class="hidden md:block">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div x-show="open" x-cloak @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border py-1">
                                <a href="{{ route('home') }}" target="_blank" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-external-link-alt mr-2"></i> Lihat Website
                                </a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
                @endif
                
                @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('scripts')
</body>
</html>
