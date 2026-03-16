@extends('frontend.layouts.app')
@section('title', 'Panduan Penelitian - ' . ($settings['site_name'] ?? 'LPPM'))
@section('content')
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Panduan Penelitian</h1>
        <nav aria-label="Breadcrumb"><ol class="flex justify-center items-center space-x-2 text-primary-200">
            <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
            <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
            <li><a href="{{ url('/penelitian/hasil') }}" class="hover:text-white">Penelitian</a></li>
            <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
            <li class="text-white">Panduan</li>
        </ol></nav>
    </div>
</div>
<section class="py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        @forelse($guides as $guide)
        <div class="mb-6 bg-white rounded-xl shadow-sm p-6 flex items-start gap-4">
            <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-book text-primary-600 text-xl"></i>
            </div>
            <div class="flex-1">
                <h2 class="text-lg font-bold text-gray-900 mb-1">{{ $guide->getTranslation()?->title ?? $guide->title }}</h2>
                <p class="text-gray-600 text-sm mb-3">{{ $guide->getTranslation()?->description ?? $guide->description }}</p>
                @if($guide->file ?? false)
                <a href="{{ asset('storage/' . $guide->file) }}" target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg text-sm hover:bg-primary-700 transition">
                    <i class="fas fa-download mr-2"></i> Unduh Panduan
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-16 text-gray-400">
            <i class="fas fa-book-open text-5xl mb-4"></i>
            <p class="text-lg">Data panduan penelitian belum tersedia.</p>
        </div>
        @endforelse
    </div>
</section>
@endsection
