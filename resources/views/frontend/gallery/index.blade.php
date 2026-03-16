@extends('frontend.layouts.app')

@section('title', 'Galeri - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Galeri Foto</h1>
        <nav class="flex justify-center" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200">
                <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white">Galeri</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($albums->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($albums as $album)
            <a href="{{ url('/galeri/' . $album->slug) }}" class="group block bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-all">
                @if($album->images->first())
                <div class="relative overflow-hidden h-48">
                    <img src="{{ asset('storage/' . $album->images->first()->image) }}" alt="{{ $album->getTranslation()?->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <i class="fas fa-images text-white text-3xl"></i>
                    </div>
                    <span class="absolute bottom-2 right-2 bg-black/60 text-white text-xs px-2 py-1 rounded">
                        {{ $album->images->count() }} foto
                    </span>
                </div>
                @else
                <div class="h-48 bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                    <i class="fas fa-images text-5xl text-primary-300"></i>
                </div>
                @endif
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">{{ $album->getTranslation()?->title }}</h3>
                    @if($album->event_date)
                    <p class="text-xs text-gray-500 mt-1"><i class="fas fa-calendar mr-1"></i>{{ \Carbon\Carbon::parse($album->event_date)->format('d M Y') }}</p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $albums->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <i class="fas fa-images text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada Album Foto</h3>
            <p class="text-gray-400">Album galeri foto akan segera ditampilkan</p>
        </div>
        @endif
    </div>
</section>
@endsection
