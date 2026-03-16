@extends('frontend.layouts.app')

@section('title', 'HKI - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Hak Kekayaan Intelektual (HKI)</h1>
        <nav class="flex justify-center" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200">
                <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li><a href="{{ url('/publikasi') }}" class="hover:text-white">Publikasi</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white">HKI</li>
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
                <option value="patent" {{ request('type') == 'patent' ? 'selected' : '' }}>Paten</option>
                <option value="copyright" {{ request('type') == 'copyright' ? 'selected' : '' }}>Hak Cipta</option>
                <option value="trademark" {{ request('type') == 'trademark' ? 'selected' : '' }}>Merek Dagang</option>
                <option value="industrial_design" {{ request('type') == 'industrial_design' ? 'selected' : '' }}>Desain Industri</option>
            </select>
        </form>

        @if($hkis->count())
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($hkis as $hki)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-certificate text-yellow-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">{{ $hki->getTranslation()?->title }}</h3>
                        @if($hki->registration_number)
                        <p class="text-sm text-gray-500 mb-1">No. Registrasi: {{ $hki->registration_number }}</p>
                        @endif
                        @if($hki->type)
                        <span class="text-xs bg-yellow-50 text-yellow-700 px-2 py-0.5 rounded">{{ ucfirst(str_replace('_', ' ', $hki->type)) }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $hkis->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <i class="fas fa-certificate text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada Data HKI</h3>
            <p class="text-gray-400">Data Hak Kekayaan Intelektual akan segera ditampilkan</p>
        </div>
        @endif
    </div>
</section>
@endsection
