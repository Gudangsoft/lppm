@extends('frontend.layouts.app')

@section('title', 'Hibah - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Hibah Penelitian</h1>
        <nav class="flex justify-center" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200">
                <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white">Hibah</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($grants->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($grants as $grant)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-award text-amber-600 text-xl"></i>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">{{ $grant->getTranslation()?->title }}</h3>
                @if($grant->getTranslation()?->description)
                <p class="text-gray-600 text-sm line-clamp-3 mb-4">{{ $grant->getTranslation()?->description }}</p>
                @endif
                <div class="flex items-center justify-between">
                    @if($grant->amount)
                    <span class="text-sm text-gray-500">Rp {{ number_format($grant->amount, 0, ',', '.') }}</span>
                    @endif
                    <a href="{{ url('/hibah/' . $grant->slug) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        Detail <i class="fas fa-arrow-right text-xs ml-1"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $grants->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <i class="fas fa-award text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada Data Hibah</h3>
            <p class="text-gray-400">Data hibah penelitian akan segera ditampilkan</p>
        </div>
        @endif
    </div>
</section>
@endsection
