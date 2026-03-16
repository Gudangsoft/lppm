@extends('frontend.layouts.app')
@section('title', 'Roadmap Penelitian - ' . ($settings['site_name'] ?? 'LPPM'))
@section('content')
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Roadmap Penelitian</h1>
        <nav aria-label="Breadcrumb"><ol class="flex justify-center items-center space-x-2 text-primary-200">
            <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
            <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
            <li><a href="{{ url('/penelitian/hasil') }}" class="hover:text-white">Penelitian</a></li>
            <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
            <li class="text-white">Roadmap</li>
        </ol></nav>
    </div>
</div>
<section class="py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        @forelse($roadmaps as $roadmap)
        <div class="mb-8 bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="bg-primary-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white">{{ $roadmap->getTranslation()?->title ?? $roadmap->title }}</h2>
            </div>
            <div class="p-6 prose max-w-none text-gray-700">
                {!! $roadmap->getTranslation()?->content ?? $roadmap->content !!}
            </div>
        </div>
        @empty
        <div class="text-center py-16 text-gray-400">
            <i class="fas fa-map text-5xl mb-4"></i>
            <p class="text-lg">Data roadmap penelitian belum tersedia.</p>
        </div>
        @endforelse
    </div>
</section>
@endsection
