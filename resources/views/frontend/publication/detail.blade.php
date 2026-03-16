@extends('frontend.layouts.app')

@section('title', ($publication->getTranslation()?->title ?? 'Detail Publikasi') . ' - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-3xl font-bold text-white mb-4">Detail Publikasi</h1>
        <nav class="flex justify-center" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200">
                <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li><a href="{{ url('/publikasi/artikel') }}" class="hover:text-white">Publikasi</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white">Detail</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm p-6 lg:p-10">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4">{{ $publication->getTranslation()?->title }}</h1>
            
            <div class="flex flex-wrap gap-3 mb-6">
                @if($publication->year)
                <span class="bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded-full">
                    <i class="fas fa-calendar mr-1"></i>{{ $publication->year }}
                </span>
                @endif
                @if($publication->journal)
                <span class="bg-primary-50 text-primary-700 text-sm px-3 py-1 rounded-full">
                    <i class="fas fa-book mr-1"></i>{{ $publication->journal->getTranslation()?->name }}
                </span>
                @endif
            </div>

            @if($publication->authors->count())
            <div class="mb-6">
                <h3 class="font-semibold text-gray-700 mb-2">Penulis</h3>
                <p class="text-gray-600">{{ $publication->authors->map(fn($a) => $a->name)->join(', ') }}</p>
            </div>
            @endif

            @if($publication->getTranslation()?->abstract)
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-xl mb-6">
                <h3 class="font-semibold text-blue-800 mb-2">Abstrak</h3>
                <p class="text-gray-700 text-sm leading-relaxed">{{ $publication->getTranslation()?->abstract }}</p>
            </div>
            @endif

            @if($publication->doi)
            <div class="mb-6">
                <h3 class="font-semibold text-gray-700 mb-2">DOI</h3>
                <a href="https://doi.org/{{ $publication->doi }}" target="_blank" class="text-primary-600 hover:text-primary-700 break-all">
                    https://doi.org/{{ $publication->doi }}
                </a>
            </div>
            @endif

            @if($publication->url)
            <a href="{{ $publication->url }}" target="_blank" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-xl font-medium transition-colors">
                <i class="fas fa-external-link-alt"></i> Lihat Publikasi Lengkap
            </a>
            @endif
        </div>

        <div class="mt-6">
            <a href="{{ url('/publikasi/artikel') }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Publikasi
            </a>
        </div>
    </div>
</section>
@endsection
