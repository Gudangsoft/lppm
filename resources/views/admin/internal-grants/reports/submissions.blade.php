@extends('admin.layouts.app')

@section('title', 'Laporan Pengajuan Hibah')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('admin.internal-grants.reports.index') }}" class="text-primary-600 hover:text-primary-700 text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Laporan Pengajuan Hibah</h1>
    </div>
    <button type="button" onclick="window.print()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
        <i class="fas fa-print mr-2"></i> Cetak
    </button>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.internal-grants.reports.submissions') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="w-40">
            <select name="year" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <option value="">Semua Tahun</option>
                @foreach($years as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-48">
            <select name="scheme" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <option value="">Semua Skema</option>
                @foreach($schemes as $scheme)
                <option value="{{ $scheme->id }}" {{ request('scheme') == $scheme->id ? 'selected' : '' }}>{{ $scheme->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
            <i class="fas fa-filter mr-2"></i> Filter
        </button>
    </form>
</div>

<!-- Summary Stats -->
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-3xl font-bold text-gray-900">{{ $summary['total'] ?? 0 }}</p>
        <p class="text-sm text-gray-500">Total</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-3xl font-bold text-yellow-600">{{ $summary['under_review'] ?? 0 }}</p>
        <p class="text-sm text-gray-500">Direview</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-3xl font-bold text-green-600">{{ $summary['accepted'] ?? 0 }}</p>
        <p class="text-sm text-gray-500">Diterima</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-3xl font-bold text-red-600">{{ $summary['rejected'] ?? 0 }}</p>
        <p class="text-sm text-gray-500">Ditolak</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-xl font-bold text-primary-600">{{ number_format($summary['acceptance_rate'] ?? 0, 1) }}%</p>
        <p class="text-sm text-gray-500">Acceptance Rate</p>
    </div>
</div>

<!-- Chart & Table -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- By Scheme -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Per Skema</h3>
        </div>
        <div class="p-6">
            @foreach($byScheme as $item)
            <div class="flex items-center justify-between mb-4">
                <div class="flex-1">
                    <p class="font-medium text-gray-900">{{ $item->scheme_name }}</p>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                        <div class="bg-primary-600 h-2 rounded-full" style="width: {{ ($item->count / max($byScheme->max('count'), 1)) * 100 }}%"></div>
                    </div>
                </div>
                <div class="text-right ml-4">
                    <p class="font-bold text-primary-600">{{ $item->count }}</p>
                    <p class="text-xs text-gray-500">{{ $item->accepted }} diterima</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- By Status -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Per Status</h3>
        </div>
        <div class="p-6">
            @foreach($byStatus as $item)
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <span class="w-3 h-3 rounded-full mr-3 {{ $item->status == 'accepted' ? 'bg-green-500' : ($item->status == 'rejected' ? 'bg-red-500' : ($item->status == 'under_review' ? 'bg-yellow-500' : 'bg-gray-500')) }}"></span>
                    <p class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $item->status) }}</p>
                </div>
                <p class="font-bold text-gray-900">{{ $item->count }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Detail Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900">Detail Pengajuan</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Registrasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Skema</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peneliti</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Anggaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($submissions as $i => $submission)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm">{{ $submissions->firstItem() + $i }}</td>
                    <td class="px-6 py-4 font-mono text-sm">{{ $submission->registration_number }}</td>
                    <td class="px-6 py-4">
                        <p class="text-sm line-clamp-2">{{ $submission->title }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $submission->period->scheme->code ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $submission->researcher->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">Rp {{ number_format($submission->requested_budget ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $submission->status_badge }}">{{ $submission->status_label }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($submissions->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $submissions->links() }}
    </div>
    @endif
</div>
@endsection
