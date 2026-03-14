<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $settings['site_description'] ?? '' }}">
    <meta name="keywords" content="{{ $settings['meta_keywords'] ?? '' }}">
    <title>@yield('title', $settings['site_name'] ?? 'LPPM')</title>
    
    @if($settings['site_favicon'] ?? false)
    <link rel="icon" href="{{ asset('storage/' . $settings['site_favicon']) }}">
    @endif
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
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
        [x-cloak] { display: none !important; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }

        /* Mobile menu slide-down animation */
        .mobile-menu-enter {
            transition: max-height 0.3s ease, opacity 0.3s ease;
        }

        /* Smooth scrollbar */
        html { scroll-behavior: smooth; }

        /* Touch-friendly tap targets */
        @media (max-width: 1024px) {
            nav a, nav button { min-height: 44px; }
        }

        /* Prevent horizontal overflow on mobile */
        body { overflow-x: hidden; }

        /* Search Overlay */
        .search-overlay-bg {
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        .search-overlay-input {
            background: white;
            border: 2px solid transparent;
            border-radius: 16px;
            padding: 1rem 1.25rem;
            font-size: 1.125rem;
            width: 100%;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .search-overlay-input:focus {
            border-color: #0284c7;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3), 0 0 0 4px rgba(2,132,199,0.15);
        }
        .search-overlay-btn {
            background: linear-gradient(135deg, #0284c7, #0ea5e9);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0.875rem 1.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s;
            white-space: nowrap;
            box-shadow: 0 4px 15px rgba(2,132,199,0.4);
        }
        .search-overlay-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(2,132,199,0.5);
        }
        .search-toggle-btn {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.85rem;
            border-radius: 999px;
            border: 1.5px solid #e2e8f0;
            background: #f8fafc;
            color: #64748b;
            font-size: 0.82rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        .search-toggle-btn:hover {
            border-color: #0284c7;
            color: #0284c7;
            background: #f0f9ff;
        }
    </style>
    
    {!! $settings['custom_head_scripts'] ?? '' !!}
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">

    <!-- Top Bar: hidden on mobile, visible on md+ -->
    <div class="hidden md:block bg-primary-800 text-white text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-2">
                <div class="flex items-center space-x-4">
                    @if($settings['contact_email'] ?? false)
                    <a href="mailto:{{ $settings['contact_email'] }}" class="flex items-center gap-1 hover:text-primary-200 transition-colors">
                        <i class="fas fa-envelope"></i>
                        <span>{{ $settings['contact_email'] }}</span>
                    </a>
                    @endif
                    @if($settings['contact_phone'] ?? false)
                    <a href="tel:{{ $settings['contact_phone'] }}" class="flex items-center gap-1 hover:text-primary-200 transition-colors">
                        <i class="fas fa-phone"></i>
                        <span>{{ $settings['contact_phone'] }}</span>
                    </a>
                    @endif
                </div>
                <div class="flex items-center space-x-3">
                    @foreach($activeLanguages ?? [] as $lang)
                    <a href="{{ url('lang/' . $lang->code) }}" 
                       class="{{ app()->getLocale() == $lang->code ? 'font-bold text-white' : 'text-primary-200' }} hover:text-white transition-colors text-xs uppercase">
                        {{ $lang->flag }} {{ $lang->code }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <!-- Header -->
    <header x-data="{ mobileMenu: false, searchOpen: false }" class="bg-white shadow-sm sticky top-0 z-50">

        {{-- === TOP ROW: Logo + Tagline + Actions === --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-[68px]">

                <!-- Logo + Tagline -->
                <a href="{{ url('/') }}" class="flex items-center gap-3 flex-shrink-0">
                    @if($settings['site_logo'] ?? false)
                    <img src="{{ asset('storage/' . $settings['site_logo']) }}"
                         alt="{{ $settings['site_name'] ?? 'LPPM' }}"
                         class="h-10 md:h-12 w-auto object-contain flex-shrink-0">
                    @endif
                    <div class="flex flex-col leading-none">
                        <span class="text-sm md:text-base font-bold text-primary-700 leading-tight whitespace-nowrap">
                            {{ $settings['site_name'] ?? 'LPPM' }}
                        </span>
                        <span class="text-[11px] text-gray-500 leading-tight whitespace-nowrap hidden sm:block mt-0.5">
                            Lembaga Penelitian &amp; Pengabdian Masyarakat
                        </span>
                    </div>
                </a>

                <!-- Right Actions -->
                <div class="flex items-center gap-2">
                    <!-- Search toggle (mobile only) -->
                    <button @click="searchOpen = true"
                            class="lg:hidden search-toggle-btn"
                            title="Cari">
                        <i class="fas fa-search text-xs"></i>
                        <span>Cari...</span>
                    </button>

                    <!-- Mobile language switcher -->
                    <div class="md:hidden flex items-center gap-1">
                        @foreach($activeLanguages ?? [] as $lang)
                        <a href="{{ url('lang/' . $lang->code) }}"
                           class="{{ app()->getLocale() == $lang->code ? 'bg-primary-600 text-white' : 'text-gray-600 bg-gray-100 hover:bg-gray-200' }} text-xs px-2 py-1 rounded font-medium transition-colors">
                            {{ strtoupper($lang->code) }}
                        </a>
                        @endforeach
                    </div>

                    <!-- Mobile menu toggle -->
                    <button @click="mobileMenu = !mobileMenu"
                            class="lg:hidden w-9 h-9 flex items-center justify-center text-gray-600 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors">
                        <i class="fas text-lg" :class="mobileMenu ? 'fa-times' : 'fa-bars'"></i>
                    </button>
                </div>
            </div>


        </div>

        {{-- === BOTTOM ROW: Desktop Navigation (hidden on mobile) === --}}
        <div class="hidden lg:block border-t border-gray-100 bg-gray-50/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                <nav class="flex items-center">
                    @foreach($mainMenu ?? [] as $item)
                    @if($item->children->count())
                    <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                        <button class="px-3 py-2 text-gray-700 hover:text-primary-600 font-medium flex items-center gap-1 text-sm rounded-lg hover:bg-primary-50 transition-colors whitespace-nowrap">
                            {{ $item->getTranslation()?->title }}
                            <i class="fas fa-chevron-down text-[10px] transition-transform duration-200" :class="open && 'rotate-180'"></i>
                        </button>
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="absolute left-0 top-full mt-1 w-52 bg-white rounded-xl shadow-xl border border-gray-100 py-2 z-50">
                            @foreach($item->children->sortBy('order') as $child)
                            <a href="{{ $child->url }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                <i class="fas fa-chevron-right text-[8px] text-primary-400"></i>
                                {{ $child->getTranslation()?->title }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <a href="{{ $item->url }}" class="px-3 py-2 text-gray-700 hover:text-primary-600 font-medium text-sm rounded-lg hover:bg-primary-50 transition-colors whitespace-nowrap">
                        {{ $item->getTranslation()?->title }}
                    </a>
                    @endif
                    @endforeach
                </nav>

                {{-- Search toggle (desktop) - sejajar dengan menu --}}
                <button @click="searchOpen = true"
                        class="search-toggle-btn flex-shrink-0"
                        title="Cari konten...">
                    <i class="fas fa-search text-xs"></i>
                    <span>Cari...</span>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu Drawer -->
        <div x-show="mobileMenu" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0 -translate-y-2"
             x-cloak
             class="lg:hidden border-t border-gray-100 bg-white max-h-[75vh] overflow-y-auto">

            <!-- Contact info on mobile (replaces hidden topbar) -->
            <div class="px-4 py-3 bg-primary-800 text-white text-xs flex flex-wrap gap-3">
                @if($settings['contact_email'] ?? false)
                <a href="mailto:{{ $settings['contact_email'] }}" class="flex items-center gap-1">
                    <i class="fas fa-envelope"></i> {{ $settings['contact_email'] }}
                </a>
                @endif
                @if($settings['contact_phone'] ?? false)
                <a href="tel:{{ $settings['contact_phone'] }}" class="flex items-center gap-1">
                    <i class="fas fa-phone"></i> {{ $settings['contact_phone'] }}
                </a>
                @endif
            </div>

            <nav class="px-4 py-3 space-y-1">
                @foreach($mainMenu ?? [] as $item)
                @if($item->children->count())
                <div x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="w-full flex justify-between items-center px-4 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-xl transition-colors font-medium">
                        <span>{{ $item->getTranslation()?->title }}</span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-transition class="pl-4 mt-1 space-y-1 border-l-2 border-primary-100 ml-4">
                        @foreach($item->children->sortBy('order') as $child)
                        <a href="{{ $child->url }}" 
                           @click="mobileMenu = false"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-colors">
                            <i class="fas fa-chevron-right text-[8px] text-primary-400"></i>
                            {{ $child->getTranslation()?->title }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @else
                <a href="{{ $item->url }}" 
                   @click="mobileMenu = false"
                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-xl transition-colors font-medium">
                    {{ $item->getTranslation()?->title }}
                </a>
                @endif
                @endforeach
            </nav>
        </div>

        {{-- ===== SEARCH OVERLAY MODAL ===== --}}
        <div x-show="searchOpen"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak
             @keydown.escape.window="searchOpen = false"
             class="search-overlay-bg fixed inset-0 z-[999] flex items-start justify-center pt-24 px-4"
             @click.self="searchOpen = false">

            <div x-show="searchOpen"
                 x-transition:enter="transition ease-out duration-250"
                 x-transition:enter-start="opacity-0 scale-95 -translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95 -translate-y-4"
                 class="w-full max-w-2xl">

                {{-- Search Card --}}
                <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

                    {{-- Header bar --}}
                    <div class="flex items-center justify-between px-5 pt-4 pb-2 border-b border-gray-100">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pencarian</span>
                        <button @click="searchOpen = false"
                                class="w-7 h-7 flex items-center justify-center rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors text-sm">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    {{-- Search Input --}}
                    <form action="{{ url('/search') }}" method="GET" class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="relative flex-1">
                                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base pointer-events-none"></i>
                                <input type="text"
                                       name="q"
                                       id="search-overlay-input"
                                       placeholder="Ketik untuk mencari..."
                                       autocomplete="off"
                                       x-init="$watch('searchOpen', v => { if(v) $nextTick(() => document.getElementById('search-overlay-input')?.focus()) })"
                                       class="search-overlay-input pl-11 pr-4"
                                       style="padding-left:2.75rem;">
                            </div>
                            <button type="submit" class="search-overlay-btn flex items-center gap-2">
                                <i class="fas fa-search text-sm"></i>
                                <span>Cari</span>
                            </button>
                        </div>
                    </form>

                    {{-- Footer hint --}}
                    <div class="px-5 pb-4 flex items-center gap-4">
                        <span class="text-xs text-gray-400 flex items-center gap-1.5">
                            <kbd class="px-1.5 py-0.5 bg-gray-100 border border-gray-200 rounded text-[10px] font-mono text-gray-500">ESC</kbd>
                            untuk menutup
                        </span>
                        <span class="text-xs text-gray-400 flex items-center gap-1.5">
                            <kbd class="px-1.5 py-0.5 bg-gray-100 border border-gray-200 rounded text-[10px] font-mono text-gray-500">Enter</kbd>
                            untuk mencari
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <!-- Main Footer -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 md:gap-12">

                <!-- About -->
                <div class="sm:col-span-2 lg:col-span-1">
                    @if($settings['site_logo'] ?? false)
                    <div class="flex items-center gap-3 mb-4">
                        <div class="bg-white rounded-xl p-2 flex-shrink-0 shadow-md">
                            <img src="{{ asset('storage/' . $settings['site_logo']) }}" 
                                 alt="{{ $settings['site_name'] ?? 'LPPM' }}" 
                                 class="h-12 w-12 object-contain">
                        </div>
                        <div>
                            <div class="text-base font-bold text-white leading-tight">{{ $settings['site_name'] ?? 'LPPM' }}</div>
                            <div class="text-xs text-primary-400 leading-tight">Lembaga Penelitian &amp; Pengabdian Masyarakat</div>
                        </div>
                    </div>
                    @else
                    <div class="text-2xl font-bold mb-4 bg-gradient-to-r from-primary-400 to-cyan-400 bg-clip-text text-transparent">{{ $settings['site_name'] ?? 'LPPM' }}</div>
                    @endif

                    <p class="text-gray-400 leading-relaxed text-sm">{{ $settings['footer_description'] ?? $settings['site_description'] ?? 'Lembaga Penelitian dan Pengabdian kepada Masyarakat' }}</p>
                    
                    <div class="flex flex-wrap gap-2 mt-5">
                        @foreach(['facebook', 'twitter', 'instagram', 'youtube', 'linkedin'] as $social)
                        @if($settings['social_' . $social] ?? false)
                        <a href="{{ $settings['social_' . $social] }}" target="_blank" 
                           class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-primary-600 transition-all hover:scale-110">
                            <i class="fab fa-{{ $social }}"></i>
                        </a>
                        @endif
                        @endforeach
                        @if(!($settings['social_facebook'] ?? false) && !($settings['social_twitter'] ?? false))
                        <a href="#" class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-primary-600 transition-all hover:scale-110"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-primary-600 transition-all hover:scale-110"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-primary-600 transition-all hover:scale-110"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-primary-600 transition-all hover:scale-110"><i class="fab fa-youtube"></i></a>
                        @endif
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="text-base font-semibold mb-4 pb-2 border-b border-gray-700">
                        {{ __('frontend.quick_links') }}
                    </h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ url('/') }}" class="text-gray-400 hover:text-white text-sm flex items-center gap-2 hover:gap-3 transition-all"><i class="fas fa-chevron-right text-[10px] text-primary-500"></i>Beranda</a></li>
                        <li><a href="{{ url('/profil/tentang') }}" class="text-gray-400 hover:text-white text-sm flex items-center gap-2 hover:gap-3 transition-all"><i class="fas fa-chevron-right text-[10px] text-primary-500"></i>Tentang Kami</a></li>
                        <li><a href="{{ url('/penelitian') }}" class="text-gray-400 hover:text-white text-sm flex items-center gap-2 hover:gap-3 transition-all"><i class="fas fa-chevron-right text-[10px] text-primary-500"></i>Penelitian</a></li>
                        <li><a href="{{ url('/pengabdian') }}" class="text-gray-400 hover:text-white text-sm flex items-center gap-2 hover:gap-3 transition-all"><i class="fas fa-chevron-right text-[10px] text-primary-500"></i>Pengabdian</a></li>
                        <li><a href="{{ url('/publikasi') }}" class="text-gray-400 hover:text-white text-sm flex items-center gap-2 hover:gap-3 transition-all"><i class="fas fa-chevron-right text-[10px] text-primary-500"></i>Publikasi</a></li>
                        <li><a href="{{ url('/berita') }}" class="text-gray-400 hover:text-white text-sm flex items-center gap-2 hover:gap-3 transition-all"><i class="fas fa-chevron-right text-[10px] text-primary-500"></i>Berita</a></li>
                    </ul>
                </div>
                
                <!-- Services -->
                <div>
                    <h4 class="text-base font-semibold mb-4 pb-2 border-b border-gray-700">Layanan</h4>
                    <ul class="space-y-2.5">
                        <li><a href="{{ url('/hibah') }}" class="text-gray-400 hover:text-white text-sm flex items-center gap-2 hover:gap-3 transition-all"><i class="fas fa-chevron-right text-[10px] text-primary-500"></i>Hibah Penelitian</a></li>
                        <li><a href="{{ url('/kerjasama') }}" class="text-gray-400 hover:text-white text-sm flex items-center gap-2 hover:gap-3 transition-all"><i class="fas fa-chevron-right text-[10px] text-primary-500"></i>Kerjasama</a></li>
                        <li><a href="{{ url('/download') }}" class="text-gray-400 hover:text-white text-sm flex items-center gap-2 hover:gap-3 transition-all"><i class="fas fa-chevron-right text-[10px] text-primary-500"></i>Download</a></li>
                        <li><a href="{{ url('/galeri') }}" class="text-gray-400 hover:text-white text-sm flex items-center gap-2 hover:gap-3 transition-all"><i class="fas fa-chevron-right text-[10px] text-primary-500"></i>Galeri</a></li>
                        <li><a href="{{ url('/agenda') }}" class="text-gray-400 hover:text-white text-sm flex items-center gap-2 hover:gap-3 transition-all"><i class="fas fa-chevron-right text-[10px] text-primary-500"></i>Agenda</a></li>
                        <li><a href="{{ url('/kontak') }}" class="text-gray-400 hover:text-white text-sm flex items-center gap-2 hover:gap-3 transition-all"><i class="fas fa-chevron-right text-[10px] text-primary-500"></i>Kontak</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h4 class="text-base font-semibold mb-4 pb-2 border-b border-gray-700">{{ __('frontend.contact') }}</h4>
                    <ul class="space-y-4 text-gray-400">
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-map-marker-alt text-primary-400 text-sm"></i>
                            </div>
                            <span class="text-sm leading-relaxed">{{ $settings['contact_address'] ?? 'Jl. Contoh No. 123, Kota' }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-primary-400 text-sm"></i>
                            </div>
                            <a href="tel:{{ $settings['contact_phone'] ?? '' }}" class="text-sm hover:text-white transition-colors">
                                {{ $settings['contact_phone'] ?? '(021) 1234567' }}
                            </a>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-envelope text-primary-400 text-sm"></i>
                            </div>
                            <a href="mailto:{{ $settings['contact_email'] ?? '' }}" class="text-sm hover:text-white transition-colors break-all">
                                {{ $settings['contact_email'] ?? 'info@lppm.ac.id' }}
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
        
        <!-- Bottom Footer -->
        <div class="border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <p class="text-center sm:text-left text-gray-500 text-xs md:text-sm">
                        {{ $settings['footer_copyright'] ?? '© ' . date('Y') . ' LPPM. All rights reserved.' }}
                    </p>
                    <div class="flex justify-center sm:justify-end gap-4 md:gap-6 text-xs md:text-sm text-gray-500 flex-wrap">
                        <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                        <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                        <a href="{{ url('/admin') }}" class="hover:text-white transition-colors">Admin</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    @if($settings['google_analytics'] ?? false)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings['google_analytics'] }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $settings['google_analytics'] }}');
    </script>
    @endif
    
    @stack('scripts')
</body>
</html>
