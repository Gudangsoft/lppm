@extends('frontend.layouts.app')

@section('title', ($grant->getTranslation()?->title ?? 'Detail Hibah') . ' - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">{{ $grant->getTranslation()?->title }}</h1>
        <nav class="flex justify-center" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200">
                <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li><a href="{{ url('/hibah') }}" class="hover:text-white">Hibah</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white">Detail</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm p-6 lg:p-10">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-6">{{ $grant->getTranslation()?->title }}</h1>

            @if($grant->amount)
            <div class="mb-6 p-4 bg-amber-50 rounded-xl border border-amber-100">
                <p class="text-sm text-amber-700">Nilai Hibah</p>
                <p class="text-2xl font-bold text-amber-800">Rp {{ number_format($grant->amount, 0, ',', '.') }}</p>
            </div>
            @endif

            @if($grant->getTranslation()?->description)
            <div class="prose prose-lg max-w-none">
                {!! $grant->getTranslation()?->description !!}
            </div>
            @endif
        </div>

        <div class="mt-6">
            <a href="{{ url('/hibah') }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Hibah
            </a>
        </div>
    </div>
</section>
@endsection
