@extends('admin.layouts.app')

@section('title', 'Laporan Hibah Aktif')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('admin.internal-grants.reports.index') }}" class="text-primary-600 hover:text-primary-700 text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Laporan Hibah Aktif</h1>
    </div>
    <button type="button" onclick="window.print()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
        <i class="fas fa-print mr-2"></i> Cetak
    </button>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.internal-grants.reports.active-grants') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="w-40">
            <select name="year" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <option value="">Semua Tahun</option>
                @foreach($years as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-48">
            <select name="status" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Dihentikan</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
            <i class="fas fa-filter mr-2"></i> Filter
        </button>
    </form>
</div>

<!-- Summary Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-3xl font-bold text-blue-600">{{ $summary['active'] ?? 0 }}</p>
        <p class="text-sm text-gray-500">Sedang Berjalan</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-3xl font-bold text-green-600">{{ $summary['completed'] ?? 0 }}</p>
        <p class="text-sm text-gray-500">Selesai</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-3xl font-bold text-yellow-600">{{ $summary['needs_report'] ?? 0 }}</p>
        <p class="text-sm text-gray-500">Perlu Laporan</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-xl font-bold text-primary-600">Rp {{ number_format($summary['total_budget'] ?? 0, 0, ',', '.') }}</p>
        <p class="text-sm text-gray-500">Total Anggaran</p>
    </div>
</div>

<!-- Detail Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Kontrak</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ketua</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Anggaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lap. Kemajuan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lap. Akhir</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($grants as $grant)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-mono text-sm">{{ $grant->contract_number }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.internal-grants.grants.show', $grant) }}" class="text-primary-600 hover:text-primary-700">
                            <p class="text-sm line-clamp-2">{{ $grant->submission->title ?? '-' }}</p>
                        </a>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $grant->submission->researcher->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">Rp {{ number_format($grant->approved_budget ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <div class="w-24">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $grant->latest_progress ?? 0 }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 text-center">{{ $grant->latest_progress ?? 0 }}%</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($grant->progressReports->count() > 0)
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">{{ $grant->progressReports->count() }}x</span>
                        @else
                        <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">Belum</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($grant->finalReport)
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Ada</span>
                        @else
                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-full">Belum</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $grant->status_badge }}">{{ $grant->status_label }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($grants->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $grants->links() }}
    </div>
    @endif
</div>
@endsection
