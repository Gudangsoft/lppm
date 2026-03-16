@extends('frontend.layouts.app')

@section('title', 'Panduan Pengabdian - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Panduan Pengabdian kepada Masyarakat</h1>
        <nav class="flex justify-center" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200">
                <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li><a href="{{ url('/pengabdian') }}" class="hover:text-white">Pengabdian</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white">Panduan</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($guides->count())
        <div class="space-y-4">
            @foreach($guides as $guide)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow flex items-start gap-4">
                <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-alt text-primary-600"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $guide->getTranslation()?->title }}</h3>
                    @if($guide->getTranslation()?->description)
                    <p class="text-gray-600 text-sm mb-3">{{ $guide->getTranslation()?->description }}</p>
                    @endif
                    @if($guide->file)
                    <a href="{{ asset('storage/' . $guide->file) }}" target="_blank" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 text-sm font-medium">
                        <i class="fas fa-download"></i> Download Panduan
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-16">
            <i class="fas fa-book-open text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada Panduan</h3>
            <p class="text-gray-400">Panduan pengabdian kepada masyarakat akan segera ditampilkan</p>
        </div>
        @endif
    </div>
</section>
@endsection
