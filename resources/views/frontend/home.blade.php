@extends('frontend.layouts.app')

@section('title', ($settings['site_name'] ?? 'LPPM') . ' - ' . ($settings['site_tagline'] ?? 'Lembaga Penelitian dan Pengabdian kepada Masyarakat'))

@push('styles')
<style>
    .hero-gradient {
        background: linear-gradient(135deg, #0c4a6e 0%, #0284c7 50%, #0ea5e9 100%);
    }
    .card-hover {
        transition: all 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .gradient-text {
        background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    .blob {
        border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%;
        animation: morph 8s ease-in-out infinite;
    }
    @keyframes morph {
        0%, 100% { border-radius: 42% 58% 70% 30% / 45% 45% 55% 55%; }
        50% { border-radius: 58% 42% 30% 70% / 55% 55% 45% 45%; }
    }
    .stat-card {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
    }
    .feature-icon {
        background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
@if($sliders->count())
<section x-data="{ activeSlide: 0 }" x-init="setInterval(() => activeSlide = (activeSlide + 1) % {{ $sliders->count() }}, 6000)" class="relative">
    <div class="relative h-[480px] sm:h-[560px] md:h-[640px] lg:h-[700px] overflow-hidden">
        @foreach($sliders as $index => $slider)
        <div x-show="activeSlide === {{ $index }}" 
             x-transition:enter="transition ease-out duration-700"
             x-transition:enter-start="opacity-0 scale-105"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $slider->image) }}')">
                <div class="absolute inset-0 bg-gradient-to-r from-primary-900/90 via-primary-800/70 to-transparent"></div>
            </div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
                <div class="max-w-2xl text-white" x-show="activeSlide === {{ $index }}" x-transition:enter="transition ease-out duration-700 delay-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold mb-4 md:mb-6 leading-tight">{{ $slider->getTranslation()?->title }}</h1>
                    <p class="text-base sm:text-lg md:text-xl text-gray-200 mb-6 md:mb-8 leading-relaxed">{{ $slider->getTranslation()?->subtitle }}</p>
                    @if($slider->url)
                    <a href="{{ $slider->url }}" class="inline-flex items-center bg-white text-primary-700 px-6 py-3 md:px-8 md:py-4 rounded-full font-bold hover:bg-primary-50 transition shadow-lg hover:shadow-xl group text-sm md:text-base">
                        {{ $slider->getTranslation()?->button_text ?? __('frontend.read_more') }}
                        <i class="fas fa-arrow-right ml-2 md:ml-3 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Slider Controls -->
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex space-x-3">
        @foreach($sliders as $index => $slider)
        <button @click="activeSlide = {{ $index }}" 
                :class="activeSlide === {{ $index }} ? 'w-10 bg-white' : 'w-3 bg-white/50 hover:bg-white/70'" 
                class="h-3 rounded-full transition-all duration-300"></button>
        @endforeach
    </div>
</section>
@else
<!-- Default Hero (No Sliders) -->
<section class="hero-gradient relative overflow-hidden min-h-[560px] md:min-h-[700px] flex items-center">
    <!-- Decorative Blobs -->
    <div class="absolute top-10 right-5 md:top-20 md:right-20 w-48 h-48 md:w-96 md:h-96 bg-white/10 blob animate-float"></div>
    <div class="absolute bottom-5 left-5 md:bottom-10 md:left-10 w-36 h-36 md:w-72 md:h-72 bg-white/5 blob animate-float" style="animation-delay: -3s;"></div>
    <div class="absolute top-1/2 left-1/3 w-48 h-48 bg-cyan-400/10 rounded-full blur-3xl"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="text-white">
                <div class="inline-flex items-center bg-white/10 backdrop-blur-sm rounded-full px-4 py-2 mb-6">
                    <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                    <span class="text-sm font-medium">{{ __('frontend.welcome') }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold mb-4 md:mb-6 leading-tight">
                    Lembaga Penelitian dan<br>
                    <span class="text-cyan-300">Pengabdian Masyarakat</span>
                </h1>
                <p class="text-sm sm:text-base md:text-lg text-gray-200 mb-6 md:mb-8 leading-relaxed max-w-xl">
                    {{ $settings['site_description'] ?? 'Mendorong inovasi melalui penelitian berkualitas dan pengabdian kepada masyarakat untuk kemajuan bangsa.' }}
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ url('/penelitian') }}" class="inline-flex items-center bg-white text-primary-700 px-5 py-3 md:px-8 md:py-4 rounded-full font-bold hover:bg-primary-50 transition shadow-lg hover:shadow-xl group text-sm md:text-base">
                        <i class="fas fa-flask mr-2"></i>
                        {{ __('frontend.explore_research') }}
                        <i class="fas fa-arrow-right ml-2 md:ml-3 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="{{ url('/profil/tentang') }}" class="inline-flex items-center bg-white/10 backdrop-blur-sm text-white px-5 py-3 md:px-8 md:py-4 rounded-full font-bold hover:bg-white/20 transition border border-white/20 text-sm md:text-base">
                        <i class="fas fa-play-circle mr-2"></i>
                        {{ __('frontend.about_us') }}
                    </a>
                </div>
            </div>
            
            <div class="hidden lg:block relative">
                <div class="relative w-full aspect-square">
                    <!-- Floating Cards -->
                    <div class="absolute top-0 right-0 bg-white rounded-2xl shadow-2xl p-6 animate-float" style="animation-delay: -1s;">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-400 rounded-xl flex items-center justify-center">
                                <i class="fas fa-microscope text-2xl text-white"></i>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-800">{{ $stats['research'] ?? 0 }}+</div>
                                <div class="text-gray-500">Penelitian</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="absolute bottom-20 left-0 bg-white rounded-2xl shadow-2xl p-6 animate-float" style="animation-delay: -2s;">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-400 rounded-xl flex items-center justify-center">
                                <i class="fas fa-hands-helping text-2xl text-white"></i>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-800">{{ $stats['pengabdian'] ?? 0 }}+</div>
                                <div class="text-gray-500">Pengabdian</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="absolute top-1/3 left-1/4 bg-white rounded-2xl shadow-2xl p-6 animate-float" style="animation-delay: -4s;">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-400 rounded-xl flex items-center justify-center">
                                <i class="fas fa-book-open text-2xl text-white"></i>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-800">{{ $stats['publications'] ?? 0 }}+</div>
                                <div class="text-gray-500">Publikasi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Wave Divider -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V120Z" fill="#f9fafb"/>
        </svg>
    </div>
</section>
@endif

<!-- Stats Section -->
<section class="relative {{ $sliders->count() ? '-mt-10 md:-mt-20' : '' }} z-10 pb-10 md:pb-16 {{ $sliders->count() ? '' : 'bg-gray-50 pt-6 md:pt-8' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6">
            <div class="stat-card rounded-xl md:rounded-2xl shadow-md md:shadow-xl p-4 md:p-8 text-center card-hover border border-gray-100">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-blue-500 to-cyan-400 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200">
                    <i class="fas fa-microscope text-2xl text-white"></i>
                </div>
                <div class="text-3xl md:text-4xl font-bold gradient-text" x-data="{ count: 0 }" x-init="setTimeout(() => { let target = {{ $stats['research'] ?? 0 }}; let step = Math.max(target / 50, 1); let interval = setInterval(() => { count += step; if(count >= target) { count = target; clearInterval(interval); } }, 30); }, 500)">
                    <span x-text="Math.round(count)">0</span>
                </div>
                <div class="text-gray-600 mt-2 font-medium">{{ __('frontend.research') }}</div>
            </div>
            
            <div class="stat-card rounded-2xl shadow-xl p-6 md:p-8 text-center card-hover border border-gray-100">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-green-500 to-emerald-400 rounded-2xl flex items-center justify-center shadow-lg shadow-green-200">
                    <i class="fas fa-hands-helping text-2xl text-white"></i>
                </div>
                <div class="text-3xl md:text-4xl font-bold gradient-text" x-data="{ count: 0 }" x-init="setTimeout(() => { let target = {{ $stats['pengabdian'] ?? 0 }}; let step = Math.max(target / 50, 1); let interval = setInterval(() => { count += step; if(count >= target) { count = target; clearInterval(interval); } }, 30); }, 700)">
                    <span x-text="Math.round(count)">0</span>
                </div>
                <div class="text-gray-600 mt-2 font-medium">{{ __('frontend.community_service') }}</div>
            </div>
            
            <div class="stat-card rounded-2xl shadow-xl p-6 md:p-8 text-center card-hover border border-gray-100">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-purple-500 to-pink-400 rounded-2xl flex items-center justify-center shadow-lg shadow-purple-200">
                    <i class="fas fa-book-open text-2xl text-white"></i>
                </div>
                <div class="text-3xl md:text-4xl font-bold gradient-text" x-data="{ count: 0 }" x-init="setTimeout(() => { let target = {{ $stats['publications'] ?? 0 }}; let step = Math.max(target / 50, 1); let interval = setInterval(() => { count += step; if(count >= target) { count = target; clearInterval(interval); } }, 30); }, 900)">
                    <span x-text="Math.round(count)">0</span>
                </div>
                <div class="text-gray-600 mt-2 font-medium">{{ __('frontend.publications') }}</div>
            </div>
            
            <div class="stat-card rounded-2xl shadow-xl p-6 md:p-8 text-center card-hover border border-gray-100">
                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-amber-500 to-orange-400 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-200">
                    <i class="fas fa-award text-2xl text-white"></i>
                </div>
                <div class="text-3xl md:text-4xl font-bold gradient-text" x-data="{ count: 0 }" x-init="setTimeout(() => { let target = {{ $stats['grants'] ?? 0 }}; let step = Math.max(target / 50, 1); let interval = setInterval(() => { count += step; if(count >= target) { count = target; clearInterval(interval); } }, 30); }, 1100)">
                    <span x-text="Math.round(count)">0</span>
                </div>
                <div class="text-gray-600 mt-2 font-medium">{{ __('frontend.grants') }}</div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-12 md:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 md:mb-16">
            <span class="inline-block px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-3 md:mb-4">{{ __('frontend.our_services') }}</span>
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 md:mb-4">Layanan LPPM</h2>
            <p class="text-base md:text-lg text-gray-600 max-w-2xl mx-auto">Mendukung civitas akademika dalam penelitian, pengabdian, dan publikasi ilmiah</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-8">
            <div class="group p-6 md:p-8 rounded-2xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 hover:border-primary-200 hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 feature-icon rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fas fa-flask text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('frontend.research') }}</h3>
                <p class="text-gray-600 leading-relaxed">Fasilitasi dan pendampingan kegiatan penelitian dosen dan mahasiswa</p>
                <a href="{{ url('/penelitian') }}" class="inline-flex items-center text-primary-600 font-semibold mt-4 group-hover:text-primary-700">
                    Selengkapnya <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
            
            <div class="group p-8 rounded-2xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 hover:border-green-200 hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-400 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fas fa-hands-helping text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('frontend.community_service') }}</h3>
                <p class="text-gray-600 leading-relaxed">Program pengabdian kepada masyarakat dan pemberdayaan komunitas</p>
                <a href="{{ url('/pengabdian') }}" class="inline-flex items-center text-green-600 font-semibold mt-4 group-hover:text-green-700">
                    Selengkapnya <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
            
            <div class="group p-8 rounded-2xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 hover:border-purple-200 hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-400 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fas fa-book-open text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('frontend.publications') }}</h3>
                <p class="text-gray-600 leading-relaxed">Pengelolaan publikasi ilmiah, jurnal, dan hak kekayaan intelektual</p>
                <a href="{{ url('/publikasi') }}" class="inline-flex items-center text-purple-600 font-semibold mt-4 group-hover:text-purple-700">
                    Selengkapnya <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
            
            <div class="group p-8 rounded-2xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 hover:border-amber-200 hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-400 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg">
                    <i class="fas fa-handshake text-2xl text-white"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ __('frontend.cooperation') }}</h3>
                <p class="text-gray-600 leading-relaxed">Kerjasama dengan institusi, industri, dan lembaga internasional</p>
                <a href="{{ url('/kerjasama') }}" class="inline-flex items-center text-amber-600 font-semibold mt-4 group-hover:text-amber-700">
                    Selengkapnya <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Latest Research -->
<section class="py-12 md:py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-12">
            <div>
                <span class="inline-block px-4 py-2 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">{{ __('frontend.latest') }}</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">{{ __('frontend.research') }}</h2>
            </div>
            <a href="{{ url('/penelitian') }}" class="inline-flex items-center text-primary-600 font-semibold hover:text-primary-700 mt-4 md:mt-0 group">
                {{ __('frontend.view_all') }} <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        @if($latestResearch->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($latestResearch as $research)
            <article class="bg-white rounded-2xl shadow-sm overflow-hidden card-hover border border-gray-100">
                <a href="{{ url('/penelitian/' . $research->slug) }}" class="block relative overflow-hidden">
                    <div class="aspect-video bg-gradient-to-br from-primary-100 to-primary-50">
                        @if($research->featured_image)
                        <img src="{{ asset('storage/' . $research->featured_image) }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-flask text-5xl text-primary-300"></i>
                        </div>
                        @endif
                    </div>
                    <span class="absolute top-4 right-4 px-3 py-1 bg-primary-600 text-white rounded-full text-xs font-medium shadow-lg">
                        {{ ucfirst($research->type ?? 'Penelitian') }}
                    </span>
                </a>
                <div class="p-6">
                    <div class="flex items-center text-sm text-gray-500 mb-3">
                        <i class="fas fa-calendar mr-2"></i>
                        <span>{{ $research->year ?? $research->created_at->format('Y') }}</span>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-3 text-lg line-clamp-2">
                        <a href="{{ url('/penelitian/' . $research->slug) }}" class="hover:text-primary-600 transition-colors">
                            {{ $research->getTranslation()?->title ?? $research->title ?? 'Judul Penelitian' }}
                        </a>
                    </h3>
                    <p class="text-gray-600 line-clamp-2 text-sm">{{ Str::limit(strip_tags($research->getTranslation()?->abstract ?? ''), 120) }}</p>
                </div>
            </article>
            @endforeach
        </div>
        @else
        <!-- Placeholder Research Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @for($i = 1; $i <= 3; $i++)
            <article class="bg-white rounded-2xl shadow-sm overflow-hidden card-hover border border-gray-100">
                <div class="aspect-video bg-gradient-to-br from-primary-100 to-primary-50 flex items-center justify-center">
                    <i class="fas fa-flask text-5xl text-primary-300"></i>
                </div>
                <div class="p-6">
                    <div class="flex items-center text-sm text-gray-500 mb-3">
                        <i class="fas fa-calendar mr-2"></i>
                        <span>2026</span>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-3 text-lg">Contoh Judul Penelitian {{ $i }}</h3>
                    <p class="text-gray-600 text-sm">Deskripsi singkat tentang penelitian yang sedang dilakukan oleh tim peneliti.</p>
                </div>
            </article>
            @endfor
        </div>
        @endif
    </div>
</section>

<!-- Latest News -->
<section class="py-12 md:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-12">
            <div>
                <span class="inline-block px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-semibold mb-4">{{ __('frontend.latest') }}</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">{{ __('frontend.news') }}</h2>
            </div>
            <a href="{{ url('/berita') }}" class="inline-flex items-center text-green-600 font-semibold hover:text-green-700 mt-4 md:mt-0 group">
                {{ __('frontend.view_all') }} <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        @if($latestNews->count())
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
            <!-- Featured News -->
            @if($latestNews->first())
            @php $featured = $latestNews->first(); @endphp
            <article class="bg-gradient-to-br from-gray-50 to-white rounded-2xl overflow-hidden card-hover border border-gray-100">
                <a href="{{ url('/berita/' . $featured->slug) }}" class="block relative overflow-hidden">
                    <div class="aspect-video bg-gradient-to-br from-green-100 to-green-50">
                        @if($featured->featured_image)
                        <img src="{{ asset('storage/' . $featured->featured_image) }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-newspaper text-5xl text-green-300"></i>
                        </div>
                        @endif
                    </div>
                </a>
                <div class="p-6">
                    @if($featured->category)
                    <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium mb-3">
                        {{ $featured->category->getTranslation()?->name ?? $featured->category->name }}
                    </span>
                    @endif
                    <h3 class="font-bold text-gray-900 mb-3 text-xl line-clamp-2">
                        <a href="{{ url('/berita/' . $featured->slug) }}" class="hover:text-green-600 transition-colors">
                            {{ $featured->getTranslation()?->title ?? $featured->title }}
                        </a>
                    </h3>
                    <p class="text-gray-600 mb-4 line-clamp-3">{{ Str::limit(strip_tags($featured->getTranslation()?->content ?? ''), 150) }}</p>
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-calendar mr-2"></i>
                        <span>{{ $featured->published_at?->format('d M Y') ?? $featured->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </article>
            @endif
            
            <!-- Other News -->
            <div class="space-y-6">
                @foreach($latestNews->skip(1) as $news)
                <article class="flex gap-6 bg-gradient-to-r from-gray-50 to-white rounded-xl p-4 card-hover border border-gray-100">
                    <a href="{{ url('/berita/' . $news->slug) }}" class="flex-shrink-0">
                        <div class="w-32 h-24 md:w-40 md:h-28 rounded-xl overflow-hidden bg-gradient-to-br from-green-100 to-green-50">
                            @if($news->featured_image)
                            <img src="{{ asset('storage/' . $news->featured_image) }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-newspaper text-2xl text-green-300"></i>
                            </div>
                            @endif
                        </div>
                    </a>
                    <div class="flex-1 min-w-0">
                        @if($news->category)
                        <span class="inline-block px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium mb-2">
                            {{ $news->category->getTranslation()?->name ?? $news->category->name }}
                        </span>
                        @endif
                        <h3 class="font-bold text-gray-900 line-clamp-2 mb-2">
                            <a href="{{ url('/berita/' . $news->slug) }}" class="hover:text-green-600 transition-colors">
                                {{ $news->getTranslation()?->title ?? $news->title }}
                            </a>
                        </h3>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-calendar mr-2"></i>
                            <span>{{ $news->published_at?->format('d M Y') ?? $news->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
        @else
        <!-- Placeholder News -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <article class="bg-gradient-to-br from-gray-50 to-white rounded-2xl overflow-hidden card-hover border border-gray-100">
                <div class="aspect-video bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center">
                    <i class="fas fa-newspaper text-5xl text-green-300"></i>
                </div>
                <div class="p-6">
                    <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium mb-3">Pengumuman</span>
                    <h3 class="font-bold text-gray-900 mb-3 text-xl">Selamat Datang di LPPM</h3>
                    <p class="text-gray-600 mb-4">Website LPPM telah aktif. Silakan kunjungi halaman admin untuk menambahkan konten.</p>
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="fas fa-calendar mr-2"></i>
                        <span>{{ now()->format('d M Y') }}</span>
                    </div>
                </div>
            </article>
            
            <div class="space-y-6">
                @for($i = 1; $i <= 3; $i++)
                <article class="flex gap-6 bg-gradient-to-r from-gray-50 to-white rounded-xl p-4 border border-gray-100">
                    <div class="flex-shrink-0">
                        <div class="w-32 h-24 md:w-40 md:h-28 rounded-xl overflow-hidden bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center">
                            <i class="fas fa-newspaper text-2xl text-green-300"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <span class="inline-block px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium mb-2">Berita</span>
                        <h3 class="font-bold text-gray-900 line-clamp-2 mb-2">Contoh Berita {{ $i }}</h3>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-calendar mr-2"></i>
                            <span>{{ now()->format('d M Y') }}</span>
                        </div>
                    </div>
                </article>
                @endfor
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Events Section -->
<section class="py-12 md:py-20 bg-gradient-to-br from-primary-800 via-primary-700 to-primary-900 relative overflow-hidden">
    <!-- Decorative -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-72 h-72 bg-white rounded-full translate-x-1/2 translate-y-1/2"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-12">
            <div>
                <span class="inline-block px-4 py-2 bg-white/20 text-white rounded-full text-sm font-semibold mb-4">{{ __('frontend.upcoming') }}</span>
                <h2 class="text-3xl md:text-4xl font-bold text-white">{{ __('frontend.events') }}</h2>
            </div>
            <a href="{{ url('/agenda') }}" class="inline-flex items-center text-white font-semibold hover:text-primary-200 mt-4 md:mt-0 group">
                {{ __('frontend.view_all') }} <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        @if(isset($upcomingEvents) && $upcomingEvents->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            @foreach($upcomingEvents as $event)
            <article class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 bg-white rounded-xl p-3 text-center min-w-[70px]">
                        <div class="text-3xl font-bold text-primary-600">{{ $event->start_date->format('d') }}</div>
                        <div class="text-sm text-gray-600 uppercase">{{ $event->start_date->format('M') }}</div>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-lg mb-2">{{ $event->getTranslation()?->title ?? $event->title }}</h3>
                        <div class="flex items-center text-primary-200 text-sm mb-2">
                            <i class="fas fa-clock mr-2"></i>
                            <span>{{ $event->start_date->format('H:i') }} WIB</span>
                        </div>
                        @if($event->location)
                        <div class="flex items-center text-primary-200 text-sm">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>{{ $event->location }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        @else
        <!-- Placeholder Events -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @for($i = 1; $i <= 3; $i++)
            <article class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 bg-white rounded-xl p-3 text-center min-w-[70px]">
                        <div class="text-3xl font-bold text-primary-600">{{ now()->addDays($i * 3)->format('d') }}</div>
                        <div class="text-sm text-gray-600 uppercase">{{ now()->addDays($i * 3)->format('M') }}</div>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-lg mb-2">Contoh Kegiatan {{ $i }}</h3>
                        <div class="flex items-center text-primary-200 text-sm mb-2">
                            <i class="fas fa-clock mr-2"></i>
                            <span>09:00 WIB</span>
                        </div>
                        <div class="flex items-center text-primary-200 text-sm">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>Kampus Utama</span>
                        </div>
                    </div>
                </div>
            </article>
            @endfor
        </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-10 md:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-primary-600 via-primary-700 to-primary-800 rounded-2xl md:rounded-3xl p-8 md:p-12 lg:p-16 text-center relative overflow-hidden">
            <!-- Decorative -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full -translate-x-1/2 translate-y-1/2"></div>
            </div>
            
            <div class="relative z-10">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-3 md:mb-4">{{ __('frontend.cta_title') }}</h2>
                <p class="text-sm md:text-lg text-primary-100 mb-6 md:mb-8 max-w-2xl mx-auto">{{ __('frontend.cta_subtitle') }}</p>
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="{{ url('/kontak') }}" class="inline-flex items-center bg-white text-primary-700 px-5 py-3 md:px-8 md:py-4 rounded-full font-bold hover:bg-primary-50 transition shadow-lg hover:shadow-xl group text-sm md:text-base">
                        <i class="fas fa-envelope mr-2"></i>
                        {{ __('frontend.contact_us') }}
                        <i class="fas fa-arrow-right ml-2 md:ml-3 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="{{ url('/kerjasama') }}" class="inline-flex items-center bg-primary-500 text-white px-5 py-3 md:px-8 md:py-4 rounded-full font-bold hover:bg-primary-400 transition border border-white/20 text-sm md:text-base">
                        <i class="fas fa-handshake mr-2"></i>
                        Ajukan Kerjasama
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Partners Section -->
<section class="py-10 md:py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-2xl font-bold text-gray-900">Mitra & Partner</h2>
        </div>
        <div class="flex flex-wrap justify-center items-center gap-5 md:gap-10">
            <!-- Placeholder Partner Logos -->
            <div class="w-32 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-sm hover:bg-gray-300 transition cursor-pointer">Partner 1</div>
            <div class="w-32 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-sm hover:bg-gray-300 transition cursor-pointer">Partner 2</div>
            <div class="w-32 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-sm hover:bg-gray-300 transition cursor-pointer">Partner 3</div>
            <div class="w-32 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-sm hover:bg-gray-300 transition cursor-pointer">Partner 4</div>
            <div class="w-32 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-sm hover:bg-gray-300 transition cursor-pointer">Partner 5</div>
        </div>
    </div>
</section>
@endsection
