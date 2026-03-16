@extends('frontend.layouts.app')
@section('title', 'Skema Hibah - ' . ($settings['site_name'] ?? 'LPPM'))
@section('content')
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Skema Hibah Penelitian</h1>
        <nav aria-label="Breadcrumb"><ol class="flex justify-center items-center space-x-2 text-primary-200">
            <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
            <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
            <li><a href="{{ url('/penelitian/hasil') }}" class="hover:text-white">Penelitian</a></li>
            <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
            <li class="text-white">Skema Hibah</li>
        </ol></nav>
    </div>
</div>
<section class="py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($schemes as $scheme)
            <div class="bg-white rounded-xl shadow-sm p-6 hover:shadow-lg transition">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-layer-group text-primary-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 mb-2">{{ $scheme->getTranslation()?->name ?? $scheme->name }}</h2>
                        <p class="text-gray-600 text-sm">{{ $scheme->getTranslation()?->description ?? $scheme->description }}</p>
                        @if($scheme->max_budget ?? false)
                        <div class="mt-3 inline-flex items-center px-3 py-1 bg-green-50 text-green-700 rounded-full text-sm">
                            <i class="fas fa-money-bill-wave mr-1"></i> Maks: Rp {{ number_format($scheme->max_budget, 0, ',', '.') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-2 text-center py-16 text-gray-400">
                <i class="fas fa-folder-open text-5xl mb-4"></i>
                <p class="text-lg">Data skema hibah belum tersedia.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
