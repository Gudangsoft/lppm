@extends('frontend.layouts.app')

@section('title', 'Laporan Pengabdian - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Laporan Pengabdian kepada Masyarakat</h1>
        <nav class="flex justify-center" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200">
                <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white">Laporan Pengabdian</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filters -->
        <form method="GET" class="flex flex-wrap gap-4 mb-8">
            <select name="program" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua Program</option>
                @foreach($programs as $program)
                <option value="{{ $program->slug }}" {{ request('program') == $program->slug ? 'selected' : '' }}>
                    {{ $program->getTranslation()?->name }}
                </option>
                @endforeach
            </select>
            <select name="year" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua Tahun</option>
                @foreach($years as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </form>

        @if($pengabdians->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($pengabdians as $pengabdian)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-2 mb-3">
                    <span class="bg-green-100 text-green-700 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ $pengabdian->year ?? date('Y') }}
                    </span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                    <a href="{{ url('/pengabdian/laporan/' . $pengabdian->slug) }}" class="hover:text-primary-600">
                        {{ $pengabdian->getTranslation()?->title }}
                    </a>
                </h3>
                @if($pengabdian->getTranslation()?->abstract)
                <p class="text-gray-600 text-sm line-clamp-3 mb-4">{{ $pengabdian->getTranslation()?->abstract }}</p>
                @endif
                <a href="{{ url('/pengabdian/laporan/' . $pengabdian->slug) }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium flex items-center gap-1">
                    Baca Selengkapnya <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $pengabdians->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <i class="fas fa-hands-helping text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada Laporan Pengabdian</h3>
            <p class="text-gray-400">Laporan pengabdian akan segera ditampilkan</p>
        </div>
        @endif
    </div>
</section>
@endsection
