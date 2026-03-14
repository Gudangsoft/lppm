@extends('frontend.layouts.app')

@section('title', __('frontend.research') . ' - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">{{ __('frontend.research') }}</h1>
        <p class="text-xl text-primary-100">{{ __('frontend.research_subtitle') }}</p>
        <nav class="flex justify-center mt-4" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200">
                <li><a href="{{ url('/') }}" class="hover:text-white">{{ __('frontend.home') }}</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white">{{ __('frontend.research') }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filter -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('frontend.search_research') }}..." class="rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <select name="type" class="rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    <option value="">{{ __('frontend.all_types') }}</option>
                    <option value="fundamental" {{ request('type') == 'fundamental' ? 'selected' : '' }}>Fundamental</option>
                    <option value="applied" {{ request('type') == 'applied' ? 'selected' : '' }}>Terapan</option>
                    <option value="development" {{ request('type') == 'development' ? 'selected' : '' }}>Pengembangan</option>
                </select>
                <select name="year" class="rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    <option value="">{{ __('frontend.all_years') }}</option>
                    @for($y = date('Y'); $y >= date('Y') - 10; $y--)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700">
                    <i class="fas fa-search mr-2"></i>{{ __('frontend.filter') }}
                </button>
            </form>
        </div>
        
        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg p-4 text-center border-l-4 border-primary-500">
                <div class="text-2xl font-bold text-primary-600">{{ $totalResearch ?? 0 }}</div>
                <div class="text-sm text-gray-600">Total Penelitian</div>
            </div>
            <div class="bg-white rounded-lg p-4 text-center border-l-4 border-green-500">
                <div class="text-2xl font-bold text-green-600">{{ $completedResearch ?? 0 }}</div>
                <div class="text-sm text-gray-600">Selesai</div>
            </div>
            <div class="bg-white rounded-lg p-4 text-center border-l-4 border-yellow-500">
                <div class="text-2xl font-bold text-yellow-600">{{ $ongoingResearch ?? 0 }}</div>
                <div class="text-sm text-gray-600">Sedang Berjalan</div>
            </div>
            <div class="bg-white rounded-lg p-4 text-center border-l-4 border-purple-500">
                <div class="text-2xl font-bold text-purple-600">{{ $fundedResearch ?? 0 }}</div>
                <div class="text-sm text-gray-600">Didanai</div>
            </div>
        </div>
        
        <!-- Research List -->
        <div class="space-y-6">
            @forelse($researches as $research)
            <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition">
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-64 flex-shrink-0">
                        <div class="aspect-video md:h-full bg-gray-200">
                            @if($research->featured_image)
                            <img src="{{ asset('storage/' . $research->featured_image) }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <i class="fas fa-flask text-4xl"></i>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="p-6 flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-xs font-medium">{{ ucfirst($research->type) }}</span>
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">{{ $research->year }}</span>
                            @if($research->status == 'completed')
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Selesai</span>
                            @elseif($research->status == 'ongoing')
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Berjalan</span>
                            @endif
                        </div>
                        
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            <a href="{{ url('/penelitian/' . $research->slug) }}" class="hover:text-primary-600">{{ $research->getTranslation()?->title }}</a>
                        </h3>
                        
                        @if($research->researchers->count())
                        <p class="text-sm text-gray-500 mb-2">
                            <i class="fas fa-users mr-1"></i>
                            {{ $research->researchers->pluck('name')->implode(', ') }}
                        </p>
                        @endif
                        
                        <p class="text-gray-600 line-clamp-2 mb-4">{{ $research->getTranslation()?->abstract }}</p>
                        
                        <a href="{{ url('/penelitian/' . $research->slug) }}" class="inline-flex items-center text-primary-600 font-medium hover:text-primary-700">
                            {{ __('frontend.view_details') }} <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </article>
            @empty
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-flask text-4xl mb-3 text-gray-300"></i>
                <p>{{ __('frontend.no_data') }}</p>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($researches->hasPages())
        <div class="mt-8">{{ $researches->links() }}</div>
        @endif
    </div>
</section>
@endsection
