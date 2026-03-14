@extends('admin.layouts.app')

@section('title', 'Laporan Realisasi Anggaran')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('admin.internal-grants.reports.index') }}" class="text-primary-600 hover:text-primary-700 text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Laporan Realisasi Anggaran</h1>
    </div>
    <button type="button" onclick="window.print()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
        <i class="fas fa-print mr-2"></i> Cetak
    </button>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.internal-grants.reports.budget-realization') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="w-40">
            <select name="year" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <option value="">Semua Tahun</option>
                @foreach($years as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
            <i class="fas fa-filter mr-2"></i> Filter
        </button>
    </form>
</div>

<!-- Summary Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-sm text-gray-500 mb-1">Total Anggaran Disetujui</p>
        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($summary['total_approved'] ?? 0, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-sm text-gray-500 mb-1">Total Dicairkan</p>
        <p class="text-2xl font-bold text-green-600">Rp {{ number_format($summary['total_disbursed'] ?? 0, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-sm text-gray-500 mb-1">Total Realisasi</p>
        <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($summary['total_realized'] ?? 0, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-sm text-gray-500 mb-1">Persentase Realisasi</p>
        <p class="text-2xl font-bold text-primary-600">{{ number_format($summary['realization_rate'] ?? 0, 1) }}%</p>
    </div>
</div>

<!-- By Scheme -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900">Realisasi Per Skema</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Skema</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Hibah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Anggaran Disetujui</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dicairkan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Realisasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">%</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($byScheme as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">{{ $item->scheme_name }}</td>
                    <td class="px-6 py-4 text-center">{{ $item->grant_count }}</td>
                    <td class="px-6 py-4">Rp {{ number_format($item->approved ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">Rp {{ number_format($item->disbursed ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">Rp {{ number_format($item->realized ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ min($item->percentage, 100) }}%"></div>
                            </div>
                            <span class="text-sm">{{ number_format($item->percentage ?? 0, 1) }}%</span>
                        </div>
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
</div>

<!-- Detail by Grant -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900">Detail Per Hibah</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No. Kontrak</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Disetujui</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dicairkan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Realisasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sisa</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($grants as $grant)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-mono text-sm">{{ $grant->contract_number }}</td>
                    <td class="px-6 py-4">
                        <p class="text-sm line-clamp-1">{{ $grant->submission->title ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4 text-sm">Rp {{ number_format($grant->approved_budget ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-sm text-green-600">Rp {{ number_format($grant->disbursements_sum_amount ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-sm text-blue-600">Rp {{ number_format($grant->finalReport->budget_realization ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-sm">
                        @php $sisa = ($grant->approved_budget ?? 0) - ($grant->finalReport->budget_realization ?? 0) @endphp
                        <span class="{{ $sisa >= 0 ? 'text-gray-600' : 'text-red-600' }}">
                            Rp {{ number_format($sisa, 0, ',', '.') }}
                        </span>
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
    @if($grants->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $grants->links() }}
    </div>
    @endif
</div>
@endsection
