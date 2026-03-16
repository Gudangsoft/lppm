@extends('frontend.layouts.app')

@section('title', ($event->getTranslation()?->title ?? 'Detail Agenda') . ' - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">{{ $event->getTranslation()?->title }}</h1>
        <nav class="flex justify-center" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200">
                <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li><a href="{{ url('/agenda') }}" class="hover:text-white">Agenda</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white">Detail</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm p-6 lg:p-10">
            <!-- Event Info -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8 p-4 bg-gray-50 rounded-xl">
                <div class="text-center sm:text-left">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Tanggal Mulai</p>
                    <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}</p>
                </div>
                @if($event->end_date)
                <div class="text-center sm:text-left">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Tanggal Selesai</p>
                    <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}</p>
                </div>
                @endif
                @if($event->location)
                <div class="text-center sm:text-left">
                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Lokasi</p>
                    <p class="font-semibold text-gray-900">{{ $event->location }}</p>
                </div>
                @endif
            </div>

            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-6">{{ $event->getTranslation()?->title }}</h1>

            @if($event->getTranslation()?->description)
            <div class="prose prose-lg max-w-none">
                {!! $event->getTranslation()?->description !!}
            </div>
            @endif
        </div>

        <div class="mt-6">
            <a href="{{ url('/agenda') }}" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-700 font-medium">
                <i class="fas fa-arrow-left"></i> Kembali ke Agenda
            </a>
        </div>
    </div>
</section>
@endsection
