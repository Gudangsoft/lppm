@extends('frontend.layouts.app')

@section('title', ($cooperation->getTranslation()?->name ?? 'Detail Kerjasama') . ' - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">{{ $cooperation->getTranslation()?->name }}</h1>
        <nav class="flex justify-center" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200">
                <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li><a href="{{ url('/kerjasama') }}" class="hover:text-white">Kerjasama</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white">Detail</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm p-6 lg:p-10">
            @if($cooperation->logo)
            <img src="{{ asset('storage/' . $cooperation->logo) }}" alt="{{ $cooperation->getTranslation()?->name }}" class="h-24 object-contain mb-6">
            @endif

            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-4">{{ $cooperation->getTranslation()?->name }}</h1>

            @if($cooperation->getTranslation()?->description)
            <div class="prose prose-lg max-w-none mb-8">
                {!! $cooperation->getTranslation()?->description !!}
            </div>
            @endif

            <div class="grid grid-cols-2 gap-4">
                @if($cooperation->type)
                <div>
                    <span class="text-sm text-gray-500">Jenis Kerjasama</span>
                    <p class="font-medium text-gray-900">{{ ucfirst($cooperation->type) }}</p>
                </div>
                @endif
                @if($cooperation->status)
                <div>
                    <span class="text-sm text-gray-500">Status</span>
                    <p class="font-medium {{ $cooperation->status == 'active' ? 'text-green-600' : 'text-gray-600' }}">
                        {{ $cooperation->status == 'active' ? 'Aktif' : 'Berakhir' }}
                    </p>
                </div>
                @endif
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ url('/kerjasama') }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kerjasama
            </a>
        </div>
    </div>
</section>
@endsection
