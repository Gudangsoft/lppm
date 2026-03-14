@extends('admin.layouts.app')

@section('title', 'Laporan Capaian Luaran')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('admin.internal-grants.reports.index') }}" class="text-primary-600 hover:text-primary-700 text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Laporan Capaian Luaran</h1>
    </div>
    <button type="button" onclick="window.print()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
        <i class="fas fa-print mr-2"></i> Cetak
    </button>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.internal-grants.reports.outputs') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="w-40">
            <select name="year" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <option value="">Semua Tahun</option>
                @foreach($years as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-48">
            <select name="type" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <option value="">Semua Jenis</option>
                <option value="publication" {{ request('type') == 'publication' ? 'selected' : '' }}>Publikasi</option>
                <option value="proceeding" {{ request('type') == 'proceeding' ? 'selected' : '' }}>Prosiding</option>
                <option value="book" {{ request('type') == 'book' ? 'selected' : '' }}>Buku</option>
                <option value="hki" {{ request('type') == 'hki' ? 'selected' : '' }}>HKI</option>
                <option value="prototype" {{ request('type') == 'prototype' ? 'selected' : '' }}>Prototipe</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
            <i class="fas fa-filter mr-2"></i> Filter
        </button>
    </form>
</div>

<!-- Summary by Type -->
<div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mx-auto mb-2">
            <i class="fas fa-book-open text-blue-600"></i>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $byType['publication'] ?? 0 }}</p>
        <p class="text-xs text-gray-500">Publikasi</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center mx-auto mb-2">
            <i class="fas fa-file-alt text-green-600"></i>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $byType['proceeding'] ?? 0 }}</p>
        <p class="text-xs text-gray-500">Prosiding</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center mx-auto mb-2">
            <i class="fas fa-book text-yellow-600"></i>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $byType['book'] ?? 0 }}</p>
        <p class="text-xs text-gray-500">Buku</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center mx-auto mb-2">
            <i class="fas fa-certificate text-purple-600"></i>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $byType['hki'] ?? 0 }}</p>
        <p class="text-xs text-gray-500">HKI</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center mx-auto mb-2">
            <i class="fas fa-cogs text-red-600"></i>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $byType['prototype'] ?? 0 }}</p>
        <p class="text-xs text-gray-500">Prototipe</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center mx-auto mb-2">
            <i class="fas fa-ellipsis-h text-gray-600"></i>
        </div>
        <p class="text-2xl font-bold text-gray-900">{{ $byType['other'] ?? 0 }}</p>
        <p class="text-xs text-gray-500">Lainnya</p>
    </div>
</div>

<!-- By Status -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Tercapai</p>
                <p class="text-2xl font-bold text-green-600">{{ $byStatus['achieved'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                <i class="fas fa-check text-green-600 text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Dalam Proses</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $byStatus['in_progress'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                <i class="fas fa-spinner text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Direncanakan</p>
                <p class="text-2xl font-bold text-gray-600">{{ $byStatus['planned'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                <i class="fas fa-clock text-gray-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Detail Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900">Daftar Luaran</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hibah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peneliti</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Link</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($outputs as $output)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-primary-100 text-primary-700">{{ $output->type_label }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium">{{ $output->title }}</p>
                        @if($output->description)
                        <p class="text-xs text-gray-500 line-clamp-1">{{ $output->description }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('admin.internal-grants.grants.show', $output->grant) }}" class="text-primary-600 hover:text-primary-700 line-clamp-1">
                            {{ $output->grant->submission->title ?? $output->grant->contract_number }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $output->grant->submission->researcher->name ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $output->status == 'achieved' ? 'bg-green-100 text-green-700' : ($output->status == 'in_progress' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                            {{ ucfirst(str_replace('_', ' ', $output->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($output->url)
                        <a href="{{ $output->url }}" target="_blank" class="text-primary-600 hover:text-primary-700">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        @else
                        -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($outputs->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $outputs->links() }}
    </div>
    @endif
</div>
@endsection
