@extends('frontend.layouts.app')

@section('title', 'Kerjasama - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Kerjasama</h1>
        <nav class="flex justify-center" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200">
                <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white">Kerjasama</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filters -->
        <form method="GET" class="flex flex-wrap gap-4 mb-8">
            <select name="type" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua Jenis</option>
                <option value="national" {{ request('type') == 'national' ? 'selected' : '' }}>Nasional</option>
                <option value="international" {{ request('type') == 'international' ? 'selected' : '' }}>Internasional</option>
            </select>
            <select name="status" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Berakhir</option>
            </select>
        </form>

        @if($cooperations->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($cooperations as $cooperation)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                @if($cooperation->logo)
                <img src="{{ asset('storage/' . $cooperation->logo) }}" alt="{{ $cooperation->getTranslation()?->name }}" class="h-16 object-contain mb-4">
                @else
                <div class="w-16 h-16 bg-primary-100 rounded-xl flex items-center justify-center mb-4">
                    <i class="fas fa-handshake text-2xl text-primary-400"></i>
                </div>
                @endif
                <h3 class="font-semibold text-gray-900 mb-2">{{ $cooperation->getTranslation()?->name }}</h3>
                @if($cooperation->getTranslation()?->description)
                <p class="text-gray-600 text-sm line-clamp-3 mb-4">{{ $cooperation->getTranslation()?->description }}</p>
                @endif
                <div class="flex items-center justify-between">
                    @if($cooperation->status)
                    <span class="text-xs {{ $cooperation->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }} px-2 py-0.5 rounded-full font-medium">
                        {{ $cooperation->status == 'active' ? 'Aktif' : 'Berakhir' }}
                    </span>
                    @endif
                    <a href="{{ url('/kerjasama/' . $cooperation->slug) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        Detail <i class="fas fa-arrow-right text-xs ml-1"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $cooperations->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <i class="fas fa-handshake text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada Data Kerjasama</h3>
            <p class="text-gray-400">Data kerjasama akan segera ditampilkan</p>
        </div>
        @endif
    </div>
</section>
@endsection
