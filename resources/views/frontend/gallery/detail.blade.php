@extends('frontend.layouts.app')

@section('title', ($album->getTranslation()?->title ?? 'Detail Galeri') . ' - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">{{ $album->getTranslation()?->title }}</h1>
        <nav class="flex justify-center" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200">
                <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li><a href="{{ url('/galeri') }}" class="hover:text-white">Galeri</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white">{{ $album->getTranslation()?->title }}</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($album->getTranslation()?->description)
        <p class="text-gray-600 text-center mb-8 max-w-2xl mx-auto">{{ $album->getTranslation()?->description }}</p>
        @endif

        @if($album->images->count())
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($album->images as $image)
            <a href="{{ asset('storage/' . $image->image) }}" target="_blank" class="group block overflow-hidden rounded-xl aspect-square">
                <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->caption ?? $album->getTranslation()?->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
            </a>
            @endforeach
        </div>
        @else
        <div class="text-center py-16">
            <i class="fas fa-images text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada Foto</h3>
            <p class="text-gray-400">Foto dalam album ini belum tersedia</p>
        </div>
        @endif

        <div class="mt-8">
            <a href="{{ url('/galeri') }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium">
                <i class="fas fa-arrow-left"></i> Kembali ke Galeri
            </a>
        </div>
    </div>
</section>
@endsection
