@extends('admin.layouts.app')

@section('title', 'Penelitian')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Penelitian</h1>
            <p class="text-gray-500 mt-1">Kelola data penelitian</p>
        </div>
        <a href="{{ route('admin.researches.create') }}" class="inline-flex items-center bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition">
            <i class="fas fa-plus mr-2"></i>Tambah Penelitian
        </a>
    </div>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
</div>
@endif

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.researches.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari penelitian..." class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-sm">
        </div>
        <div>
            <select name="scheme" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-sm">
                <option value="">Semua Skema</option>
                @foreach($schemes as $scheme)
                <option value="{{ $scheme->id }}" {{ request('scheme') == $scheme->id ? 'selected' : '' }}>{{ $scheme->getTranslation()?->name ?? $scheme->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select name="year" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-sm">
                <option value="">Semua Tahun</option>
                @foreach($years as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select name="status" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 text-sm">
                <option value="">Semua Status</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Berjalan</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Dipublikasi</option>
            </select>
        </div>
        <div class="flex space-x-2">
            <button type="submit" class="flex-1 bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition text-sm">
                <i class="fas fa-search mr-1"></i>Filter
            </button>
            <a href="{{ route('admin.researches.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Data Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Skema</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tahun</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tim</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($researches as $research)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($research->featured_image)
                            <img src="{{ asset('storage/' . $research->featured_image) }}" class="w-12 h-12 rounded-lg object-cover mr-4">
                            @else
                            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-flask text-primary-500"></i>
                            </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-900 line-clamp-1">{{ $research->getTranslation()?->title ?? '-' }}</p>
                                <p class="text-sm text-gray-500">{{ $research->slug }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($research->scheme)
                        <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded">
                            {{ $research->scheme->getTranslation()?->name ?? $research->scheme->code }}
                        </span>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $research->year ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @php
                            $statusClasses = [
                                'draft' => 'bg-gray-100 text-gray-700',
                                'ongoing' => 'bg-yellow-100 text-yellow-700',
                                'completed' => 'bg-green-100 text-green-700',
                                'published' => 'bg-blue-100 text-blue-700',
                            ];
                            $statusLabels = [
                                'draft' => 'Draft',
                                'ongoing' => 'Berjalan',
                                'completed' => 'Selesai',
                                'published' => 'Dipublikasi',
                            ];
                        @endphp
                        <span class="px-2 py-1 text-xs font-medium rounded {{ $statusClasses[$research->status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $statusLabels[$research->status] ?? ucfirst($research->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($research->team && $research->team->count() > 0)
                        <div class="flex -space-x-2">
                            @foreach($research->team->take(3) as $member)
                            <div class="w-8 h-8 bg-primary-100 rounded-full border-2 border-white flex items-center justify-center text-xs font-medium text-primary-700" title="{{ $member->researcher?->name ?? $member->member_name }}">
                                {{ substr($member->researcher?->name ?? $member->member_name ?? 'X', 0, 1) }}
                            </div>
                            @endforeach
                            @if($research->team->count() > 3)
                            <div class="w-8 h-8 bg-gray-200 rounded-full border-2 border-white flex items-center justify-center text-xs font-medium text-gray-600">
                                +{{ $research->team->count() - 3 }}
                            </div>
                            @endif
                        </div>
                        @else
                        <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('admin.researches.edit', $research) }}" class="text-primary-600 hover:text-primary-800" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.researches.destroy', $research) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus penelitian ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-flask text-4xl text-gray-300 mb-3"></i>
                        <p class="mt-2">Belum ada data penelitian</p>
                        <a href="{{ route('admin.researches.create') }}" class="inline-flex items-center mt-3 text-primary-600 hover:text-primary-700">
                            <i class="fas fa-plus mr-1"></i>Tambah Penelitian Pertama
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($researches->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $researches->withQueryString()->links() }}
    </div>
    @endif
</div>

<!-- Stats -->
<div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-flask text-blue-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Research::count() }}</p>
                <p class="text-xs text-gray-500">Total Penelitian</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-spinner text-yellow-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Research::where('status', 'ongoing')->count() }}</p>
                <p class="text-xs text-gray-500">Sedang Berjalan</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-check-circle text-green-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Research::where('status', 'completed')->count() }}</p>
                <p class="text-xs text-gray-500">Selesai</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-globe text-purple-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Research::where('status', 'published')->count() }}</p>
                <p class="text-xs text-gray-500">Dipublikasi</p>
            </div>
        </div>
    </div>
</div>
@endsection
