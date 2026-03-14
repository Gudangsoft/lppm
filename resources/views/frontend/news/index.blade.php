@extends('frontend.layouts.app')

@section('title', __('frontend.news') . ' - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-white mb-4">{{ __('frontend.news') }}</h1>
            <nav class="flex justify-center" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-primary-200">
                    <li><a href="{{ url('/') }}" class="hover:text-white">{{ __('frontend.home') }}</a></li>
                    <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                    <li class="text-white">{{ __('frontend.news') }}</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content -->
            <div class="flex-1">
                <!-- Filter -->
                <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
                    <form method="GET" class="flex flex-wrap gap-4">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('frontend.search') }}..." class="flex-1 min-w-[200px] rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                        <select name="category" class="rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                            <option value="">{{ __('frontend.all_categories') }}</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>{{ $category->getTranslation()?->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700">
                            <i class="fas fa-search mr-1"></i>{{ __('frontend.filter') }}
                        </button>
                    </form>
                </div>
                
                <!-- News Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($news as $item)
                    <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition">
                        <a href="{{ url('/berita/' . $item->slug) }}">
                            <div class="aspect-video bg-gray-200">
                                @if($item->featured_image)
                                <img src="{{ asset('storage/' . $item->featured_image) }}" class="w-full h-full object-cover" alt="{{ $item->getTranslation()?->title }}">
                                @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fas fa-newspaper text-4xl"></i></div>
                                @endif
                            </div>
                        </a>
                        <div class="p-5">
                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                @if($item->category)
                                <span class="px-2 py-1 bg-primary-100 text-primary-700 rounded text-xs font-medium">{{ $item->category->getTranslation()?->name }}</span>
                                <span class="mx-2">•</span>
                                @endif
                                <span><i class="fas fa-calendar mr-1"></i>{{ $item->published_at?->format('d M Y') }}</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                <a href="{{ url('/berita/' . $item->slug) }}" class="hover:text-primary-600">{{ $item->getTranslation()?->title }}</a>
                            </h3>
                            <p class="text-gray-600 line-clamp-2">{{ $item->getTranslation()?->excerpt }}</p>
                            <a href="{{ url('/berita/' . $item->slug) }}" class="inline-flex items-center text-primary-600 font-medium mt-3 hover:text-primary-700">
                                {{ __('frontend.read_more') }} <i class="fas fa-arrow-right ml-1 text-sm"></i>
                            </a>
                        </div>
                    </article>
                    @empty
                    <div class="col-span-2 text-center py-12 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                        <p>{{ __('frontend.no_data') }}</p>
                    </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                @if($news->hasPages())
                <div class="mt-8">{{ $news->links() }}</div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <aside class="lg:w-80 space-y-6">
                <!-- Categories -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">{{ __('frontend.categories') }}</h3>
                    <ul class="space-y-2">
                        @foreach($categories as $category)
                        <li>
                            <a href="{{ url('/berita?category=' . $category->slug) }}" class="flex justify-between items-center text-gray-600 hover:text-primary-600 py-1">
                                {{ $category->getTranslation()?->name }}
                                <span class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $category->news_count ?? 0 }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                
                <!-- Popular News -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">{{ __('frontend.popular') }}</h3>
                    <div class="space-y-4">
                        @foreach($popularNews as $item)
                        <article class="flex gap-3">
                            <div class="w-20 h-16 flex-shrink-0 bg-gray-200 rounded">
                                @if($item->featured_image)
                                <img src="{{ asset('storage/' . $item->featured_image) }}" class="w-full h-full object-cover rounded">
                                @endif
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 line-clamp-2">
                                    <a href="{{ url('/berita/' . $item->slug) }}" class="hover:text-primary-600">{{ $item->getTranslation()?->title }}</a>
                                </h4>
                                <span class="text-xs text-gray-500">{{ $item->published_at?->format('d M Y') }}</span>
                            </div>
                        </article>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
