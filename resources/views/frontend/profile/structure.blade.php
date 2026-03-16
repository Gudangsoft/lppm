@extends('frontend.layouts.app')
@section('title', 'Struktur Organisasi - ' . ($settings['site_name'] ?? 'LPPM'))
@section('content')
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Struktur Organisasi</h1>
        <nav aria-label="Breadcrumb"><ol class="flex justify-center items-center space-x-2 text-primary-200">
            <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
            <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
            <li><span class="text-white">Struktur Organisasi</span></li>
        </ol></nav>
    </div>
</div>
<section class="py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        @forelse($structures as $structure)
        <div class="mb-8 bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-primary-700 mb-4 border-b pb-3">{{ $structure->getTranslation()?->name ?? $structure->name }}</h2>
            @if($structure->children->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-4">
                @foreach($structure->children as $child)
                <div class="bg-primary-50 rounded-lg p-4 text-center">
                    <div class="text-sm font-semibold text-primary-800">{{ $child->getTranslation()?->name ?? $child->name }}</div>
                    @if($child->position ?? false)<div class="text-xs text-gray-500 mt-1">{{ $child->position }}</div>@endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @empty
        <div class="text-center py-16 text-gray-400">
            <i class="fas fa-sitemap text-5xl mb-4"></i>
            <p class="text-lg">Data struktur organisasi belum tersedia.</p>
        </div>
        @endforelse
    </div>
</section>
@endsection
