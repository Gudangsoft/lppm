@extends('frontend.layouts.app')
@section('title', ($research->getTranslation()?->title ?? $research->title) . ' - ' . ($settings['site_name'] ?? 'LPPM'))
@section('content')
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Detail Penelitian</h1>
        <nav aria-label="Breadcrumb"><ol class="flex justify-center items-center space-x-2 text-primary-200">
            <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
            <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
            <li><a href="{{ url('/penelitian/hasil') }}" class="hover:text-white">Hasil Penelitian</a></li>
            <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
            <li class="text-white truncate max-w-xs">{{ $research->getTranslation()?->title ?? $research->title }}</li>
        </ol></nav>
    </div>
</div>
<section class="py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $research->getTranslation()?->title ?? $research->title }}</h1>
                <div class="flex flex-wrap gap-3 mb-6">
                    @if($research->year)<span class="px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-sm"><i class="fas fa-calendar mr-1"></i>{{ $research->year }}</span>@endif
                    @if($research->scheme ?? false)<span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm"><i class="fas fa-layer-group mr-1"></i>{{ $research->scheme->getTranslation()?->name ?? $research->scheme->name }}</span>@endif
                    @if($research->status ?? false)<span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm"><i class="fas fa-info-circle mr-1"></i>{{ ucfirst($research->status) }}</span>@endif
                </div>
                @if($research->team ?? false)
                <div class="mb-6">
                    <h3 class="font-semibold text-gray-700 mb-2">Tim Peneliti</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($research->team as $member)
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $member->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
                <div class="prose max-w-none text-gray-700">
                    {!! $research->getTranslation()?->abstract ?? $research->getTranslation()?->content ?? '<p class="text-gray-500">Konten tidak tersedia.</p>' !!}
                </div>
            </div>
        </div>
        <div class="mt-6">
            <a href="{{ url('/penelitian/hasil') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Penelitian
            </a>
        </div>
    </div>
</section>
@endsection
