@extends('frontend.layouts.app')

@section('title', 'Publikasi - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Publikasi Ilmiah</h1>
        <nav class="flex justify-center" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200">
                <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white">Publikasi</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filters -->
        <form method="GET" class="flex flex-wrap gap-4 mb-8">
            <select name="year" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <option value="">Semua Tahun</option>
                @foreach($years as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </form>

        @if($publications->count())
        <div class="space-y-4">
            @foreach($publications as $pub)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-file-alt text-primary-600"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base font-semibold text-gray-900 mb-1 line-clamp-2">
                            <a href="{{ url('/publikasi/artikel/' . $pub->slug) }}" class="hover:text-primary-600">
                                {{ $pub->getTranslation()?->title }}
                            </a>
                        </h3>
                        @if($pub->authors->count())
                        <p class="text-sm text-gray-500 mb-2">
                            {{ $pub->authors->map(fn($a) => $a->name)->join(', ') }}
                        </p>
                        @endif
                        <div class="flex flex-wrap gap-2">
                            @if($pub->year)
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">{{ $pub->year }}</span>
                            @endif
                            @if($pub->journal)
                            <span class="text-xs bg-primary-50 text-primary-700 px-2 py-0.5 rounded">{{ $pub->journal->getTranslation()?->name }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $publications->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <i class="fas fa-file-alt text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada Publikasi</h3>
            <p class="text-gray-400">Publikasi ilmiah akan segera ditampilkan</p>
        </div>
        @endif
    </div>
</section>
@endsection
