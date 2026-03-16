@extends('frontend.layouts.app')

@section('title', 'Jurnal Ilmiah - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Jurnal Ilmiah</h1>
        <nav class="flex justify-center" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200">
                <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li><a href="{{ url('/publikasi') }}" class="hover:text-white">Publikasi</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white">Jurnal</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($journals->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($journals as $journal)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow group">
                @if($journal->cover_image)
                <img src="{{ asset('storage/' . $journal->cover_image) }}" alt="{{ $journal->getTranslation()?->name }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                <div class="w-full h-48 bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                    <i class="fas fa-journal-whills text-5xl text-primary-400"></i>
                </div>
                @endif
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $journal->getTranslation()?->name }}</h3>
                    @if($journal->issn)
                    <p class="text-sm text-gray-500 mb-2"><span class="font-medium">ISSN:</span> {{ $journal->issn }}</p>
                    @endif
                    @if($journal->getTranslation()?->description)
                    <p class="text-gray-600 text-sm line-clamp-3 mb-4">{{ $journal->getTranslation()?->description }}</p>
                    @endif
                    @if($journal->url)
                    <a href="{{ $journal->url }}" target="_blank" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 text-sm font-medium">
                        <i class="fas fa-external-link-alt text-xs"></i> Kunjungi Jurnal
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-16">
            <i class="fas fa-book text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada Jurnal</h3>
            <p class="text-gray-400">Jurnal ilmiah akan segera ditampilkan</p>
        </div>
        @endif
    </div>
</section>
@endsection
