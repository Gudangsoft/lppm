@extends('frontend.layouts.app')

@section('title', ($page->getTranslation()?->title ?? 'Page') . ' - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16" @if($page->featured_image) style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('{{ asset('storage/' . $page->featured_image) }}'); background-size: cover; background-position: center;" @endif>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">{{ $page->getTranslation()?->title }}</h1>
        <nav class="flex justify-center" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200">
                <li><a href="{{ url('/') }}" class="hover:text-white">{{ __('frontend.home') }}</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white">{{ $page->getTranslation()?->title }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($page->template == 'full-width')
        <!-- Full Width Template -->
        <div class="bg-white rounded-xl shadow-sm p-6 lg:p-10">
            <div class="prose prose-lg max-w-none">
                {!! $page->getTranslation()?->content !!}
            </div>
        </div>
        
        @elseif($page->template == 'sidebar')
        <!-- Sidebar Template -->
        <div class="flex flex-col lg:flex-row gap-8">
            <div class="flex-1">
                <div class="bg-white rounded-xl shadow-sm p-6 lg:p-8">
                    <div class="prose prose-lg max-w-none">
                        {!! $page->getTranslation()?->content !!}
                    </div>
                </div>
            </div>
            <aside class="lg:w-80 space-y-6">
                <!-- Quick Links -->
                @if($relatedPages->count())
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">{{ __('frontend.quick_links') }}</h3>
                    <ul class="space-y-2">
                        @foreach($relatedPages as $related)
                        <li>
                            <a href="{{ url('/page/' . $related->slug) }}" class="flex items-center text-gray-600 hover:text-primary-600 py-1">
                                <i class="fas fa-chevron-right text-xs mr-2 text-primary-500"></i>
                                {{ $related->getTranslation()?->title }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <!-- Contact Card -->
                <div class="bg-primary-600 rounded-xl shadow-sm p-6 text-white">
                    <h3 class="font-semibold mb-4">{{ __('frontend.need_help') }}</h3>
                    <p class="text-primary-100 text-sm mb-4">{{ __('frontend.contact_us_desc') }}</p>
                    <a href="{{ url('/kontak') }}" class="inline-flex items-center bg-white text-primary-600 px-4 py-2 rounded-lg font-medium hover:bg-primary-50 transition">
                        <i class="fas fa-envelope mr-2"></i>{{ __('frontend.contact_us') }}
                    </a>
                </div>
            </aside>
        </div>
        
        @else
        <!-- Default Template -->
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-sm p-6 lg:p-10">
                <div class="prose prose-lg max-w-none">
                    {!! $page->getTranslation()?->content !!}
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
