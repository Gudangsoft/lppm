@extends('frontend.layouts.app')

@section('title', 'Download - ' . ($settings['site_name'] ?? 'LPPM'))

@section('content')
<!-- Page Header -->
<div class="bg-gradient-to-r from-primary-700 to-primary-600 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold text-white mb-4">Pusat Unduhan</h1>
        <nav class="flex justify-center" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-primary-200">
                <li><a href="{{ url('/') }}" class="hover:text-white">Beranda</a></li>
                <li><i class="fas fa-chevron-right text-xs mx-2"></i></li>
                <li class="text-white">Download</li>
            </ol>
        </nav>
    </div>
</div>

<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Categories -->
            @if($categories->count())
            <aside class="lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Kategori</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ url('/download') }}" class="flex items-center gap-2 text-sm {{ !request('category') ? 'text-primary-600 font-medium' : 'text-gray-600 hover:text-primary-600' }} py-1">
                                <i class="fas fa-folder text-xs"></i> Semua File
                            </a>
                        </li>
                        @foreach($categories as $cat)
                        <li>
                            <a href="{{ url('/download') }}?category={{ $cat->slug }}" class="flex items-center gap-2 text-sm {{ request('category') == $cat->slug ? 'text-primary-600 font-medium' : 'text-gray-600 hover:text-primary-600' }} py-1">
                                <i class="fas fa-folder text-xs"></i>
                                {{ $cat->getTranslation()?->name }}
                                <span class="ml-auto text-xs bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded">{{ $cat->downloads_count ?? 0 }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </aside>
            @endif

            <!-- Downloads List -->
            <div class="flex-1">
                @if($downloads->count())
                <div class="space-y-3">
                    @foreach($downloads as $download)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow flex items-center gap-4">
                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            @php
                                $ext = pathinfo($download->file ?? '', PATHINFO_EXTENSION);
                                $icon = match($ext) {
                                    'pdf' => 'fa-file-pdf text-red-500',
                                    'doc', 'docx' => 'fa-file-word text-blue-500',
                                    'xls', 'xlsx' => 'fa-file-excel text-green-500',
                                    'ppt', 'pptx' => 'fa-file-powerpoint text-orange-500',
                                    'zip', 'rar' => 'fa-file-archive text-yellow-500',
                                    default => 'fa-file text-gray-500'
                                };
                            @endphp
                            <i class="fas {{ $icon }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-medium text-gray-900 truncate">{{ $download->getTranslation()?->title }}</h3>
                            @if($download->getTranslation()?->description)
                            <p class="text-gray-500 text-sm truncate">{{ $download->getTranslation()?->description }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            @if($download->download_count)
                            <span class="text-xs text-gray-400">{{ $download->download_count }} unduhan</span>
                            @endif
                            <a href="{{ route('download.file', $download) }}" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm px-4 py-2 rounded-lg transition-colors">
                                <i class="fas fa-download"></i> Unduh
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $downloads->links() }}
                </div>
                @else
                <div class="text-center py-16 bg-white rounded-xl border border-gray-100">
                    <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-500 mb-2">Belum Ada File</h3>
                    <p class="text-gray-400">File unduhan akan segera tersedia</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
