@extends('frontend.layouts.app')

@section('title', 'Agenda - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Agenda Kegiatan</h1>
        <nav class="flex justify-center" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200">
                <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white">Agenda</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($events->count())
        <div class="space-y-4">
            @foreach($events as $event)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow flex gap-6">
                <!-- Date Block -->
                <div class="flex-shrink-0 text-center">
                    <div class="bg-primary-600 text-white rounded-xl px-4 py-3 min-w-[64px]">
                        <div class="text-2xl font-bold leading-none">{{ \Carbon\Carbon::parse($event->start_date)->format('d') }}</div>
                        <div class="text-xs uppercase mt-0.5">{{ \Carbon\Carbon::parse($event->start_date)->format('M Y') }}</div>
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">
                        <a href="{{ url('/agenda/' . $event->slug) }}" class="hover:text-primary-600">
                            {{ $event->getTranslation()?->title }}
                        </a>
                    </h3>
                    @if($event->location)
                    <p class="text-gray-500 text-sm mb-2">
                        <i class="fas fa-map-marker-alt mr-1 text-primary-400"></i> {{ $event->location }}
                    </p>
                    @endif
                    @if($event->getTranslation()?->description)
                    <p class="text-gray-600 text-sm line-clamp-2">{{ strip_tags($event->getTranslation()?->description) }}</p>
                    @endif
                </div>

                <div class="flex-shrink-0">
                    <a href="{{ url('/agenda/' . $event->slug) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium flex items-center gap-1">
                        Detail <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $events->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <i class="fas fa-calendar-alt text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada Agenda</h3>
            <p class="text-gray-400">Agenda kegiatan akan segera ditampilkan</p>
        </div>
        @endif
    </div>
</section>
@endsection
