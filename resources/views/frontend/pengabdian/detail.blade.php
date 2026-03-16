@extends('frontend.layouts.app')

@section('title', ($pengabdian->getTranslation()?->title ?? 'Detail Pengabdian') . ' - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">{{ $pengabdian->getTranslation()?->title }}</h1>
        <nav class="flex justify-center" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200">
                <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li><a href="{{ url('/pengabdian/laporan') }}" class="hover:text-white">Pengabdian</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white">Detail</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm p-6 lg:p-10">
            <div class="flex flex-wrap gap-3 mb-6">
                @if($pengabdian->year)
                <span class="bg-green-100 text-green-700 text-sm font-medium px-3 py-1 rounded-full">
                    <i class="fas fa-calendar mr-1"></i>{{ $pengabdian->year }}
                </span>
                @endif
            </div>
            
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-6">{{ $pengabdian->getTranslation()?->title }}</h1>
            
            @if($pengabdian->getTranslation()?->abstract)
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-xl mb-8">
                <h3 class="font-semibold text-blue-800 mb-2">Abstrak</h3>
                <p class="text-gray-700 text-sm leading-relaxed">{{ $pengabdian->getTranslation()?->abstract }}</p>
            </div>
            @endif

            @if($pengabdian->team->count())
            <div class="mb-8">
                <h3 class="font-semibold text-gray-900 mb-3">Tim Pelaksana</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($pengabdian->team as $member)
                    <span class="bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded-full">
                        {{ $member->name }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        <div class="mt-6">
            <a href="{{ url('/pengabdian/laporan') }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pengabdian
            </a>
        </div>
    </div>
</section>
@endsection
