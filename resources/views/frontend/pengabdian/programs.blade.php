@extends('frontend.layouts.app')

@section('title', __('frontend.pkm_programs') . ' - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Program Pengabdian kepada Masyarakat</h1>
        <nav class="flex justify-center" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200">
                <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li><a href="{{ url('/pengabdian') }}" class="hover:text-white">Pengabdian</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white">Program PkM</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($programs->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($programs as $program)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                @if($program->image)
                <img src="{{ asset('storage/' . $program->image) }}" alt="{{ $program->getTranslation()?->name }}" class="w-full h-48 object-cover">
                @else
                <div class="w-full h-48 bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                    <i class="fas fa-hands-helping text-5xl text-primary-400"></i>
                </div>
                @endif
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $program->getTranslation()?->name }}</h3>
                    @if($program->getTranslation()?->description)
                    <p class="text-gray-600 text-sm line-clamp-3">{{ $program->getTranslation()?->description }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-16">
            <i class="fas fa-hands-helping text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada Program</h3>
            <p class="text-gray-400">Program pengabdian kepada masyarakat akan segera ditampilkan</p>
        </div>
        @endif
    </div>
</section>
@endsection
