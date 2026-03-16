@extends('frontend.layouts.app')

@section('title', 'Repository - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Repository</h1>
        <nav class="flex justify-center" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200">
                <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li><a href="{{ url('/publikasi') }}" class="hover:text-white">Publikasi</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white">Repository</li>
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
                <option value="thesis" {{ request('type') == 'thesis' ? 'selected' : '' }}>Tesis</option>
                <option value="dissertation" {{ request('type') == 'dissertation' ? 'selected' : '' }}>Disertasi</option>
                <option value="report" {{ request('type') == 'report' ? 'selected' : '' }}>Laporan</option>
            </select>
            <select name="year" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua Tahun</option>
                @for($y = date('Y'); $y >= 2000; $y--)
                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </form>

        @if($repositories->count())
        <div class="space-y-4">
            @foreach($repositories as $repo)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow flex items-start gap-4">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-database text-purple-600 text-sm"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 mb-1">{{ $repo->getTranslation()?->title }}</h3>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @if($repo->year)
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">{{ $repo->year }}</span>
                        @endif
                        @if($repo->type)
                        <span class="text-xs bg-purple-50 text-purple-700 px-2 py-0.5 rounded">{{ ucfirst($repo->type) }}</span>
                        @endif
                    </div>
                </div>
                @if($repo->file)
                <a href="{{ asset('storage/' . $repo->file) }}" target="_blank" class="text-primary-600 hover:text-primary-700 flex-shrink-0">
                    <i class="fas fa-download"></i>
                </a>
                @endif
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $repositories->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <i class="fas fa-database text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada Data Repository</h3>
            <p class="text-gray-400">Data repository akan segera ditampilkan</p>
        </div>
        @endif
    </div>
</section>
@endsection
