@extends('frontend.layouts.app')

@section('title', $news->getTranslation()?->title . ' - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200 text-sm">
                <li><a href="{{ url('/') }}" class="hover:text-white">{{ __('frontend.home') }}</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li><a href="{{ url('/berita') }}" class="hover:text-white">{{ __('frontend.news') }}</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white truncate max-w-xs">{{ $news->getTranslation()?->title }}</li>
            </ol>
        </nav>
    </div>
</div>

<article class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main Content -->
            <div class="flex-1">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    @if($news->featured_image)
                    <div class="aspect-video">
                        <img src="{{ asset('storage/' . $news->featured_image) }}" class="w-full h-full object-cover" alt="{{ $news->getTranslation()?->title }}">
                    </div>
                    @endif
                    
                    <div class="p-6 lg:p-8">
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-4">
                            @if($news->category)
                            <span class="px-3 py-1 bg-primary-100 text-primary-700 rounded-full font-medium">{{ $news->category->getTranslation()?->name }}</span>
                            @endif
                            <span><i class="fas fa-calendar mr-1"></i>{{ $news->published_at?->format('d F Y') }}</span>
                            <span><i class="fas fa-user mr-1"></i>{{ $news->author?->name ?? 'Admin' }}</span>
                            <span><i class="fas fa-eye mr-1"></i>{{ $news->views ?? 0 }} views</span>
                        </div>
                        
                        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">{{ $news->getTranslation()?->title }}</h1>
                        
                        @if($news->getTranslation()?->excerpt)
                        <p class="text-xl text-gray-600 mb-6 leading-relaxed">{{ $news->getTranslation()?->excerpt }}</p>
                        @endif
                        
                        <div class="prose prose-lg max-w-none">
                            {!! $news->getTranslation()?->content !!}
                        </div>
                        
                        <!-- Share -->
                        <div class="border-t border-gray-100 mt-8 pt-6">
                            <div class="flex items-center justify-between flex-wrap gap-4">
                                <div class="flex items-center space-x-2">
                                    <span class="text-gray-600 font-medium">{{ __('frontend.share') }}:</span>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($news->getTranslation()?->title) }}" target="_blank" class="w-10 h-10 bg-sky-500 text-white rounded-full flex items-center justify-center hover:bg-sky-600">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a href="https://wa.me/?text={{ urlencode($news->getTranslation()?->title . ' ' . request()->url()) }}" target="_blank" class="w-10 h-10 bg-green-500 text-white rounded-full flex items-center justify-center hover:bg-green-600">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </div>
                                <a href="{{ url('/berita') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                                    <i class="fas fa-arrow-left mr-1"></i>{{ __('frontend.back_to_list') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Related News -->
                @if($relatedNews->count())
                <div class="mt-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">{{ __('frontend.related_news') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedNews as $item)
                        <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition">
                            <a href="{{ url('/berita/' . $item->slug) }}">
                                <div class="aspect-video bg-gray-200">
                                    @if($item->featured_image)
                                    <img src="{{ asset('storage/' . $item->featured_image) }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                            </a>
                            <div class="p-4">
                                <h4 class="font-semibold text-gray-900 line-clamp-2 hover:text-primary-600">
                                    <a href="{{ url('/berita/' . $item->slug) }}">{{ $item->getTranslation()?->title }}</a>
                                </h4>
                                <span class="text-xs text-gray-500 mt-1">{{ $item->published_at?->format('d M Y') }}</span>
                            </div>
                        </article>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <aside class="lg:w-80 space-y-6">
                <!-- Latest News -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">{{ __('frontend.latest_news') }}</h3>
                    <div class="space-y-4">
                        @foreach($latestNews as $item)
                        <article class="flex gap-3">
                            <div class="w-20 h-16 flex-shrink-0 bg-gray-200 rounded overflow-hidden">
                                @if($item->featured_image)
                                <img src="{{ asset('storage/' . $item->featured_image) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900 line-clamp-2 hover:text-primary-600">
                                    <a href="{{ url('/berita/' . $item->slug) }}">{{ $item->getTranslation()?->title }}</a>
                                </h4>
                                <span class="text-xs text-gray-500">{{ $item->published_at?->format('d M Y') }}</span>
                            </div>
                        </article>
                        @endforeach
                    </div>
                </div>
                
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
            </aside>
        </div>
    </div>
</article>
@endsection
