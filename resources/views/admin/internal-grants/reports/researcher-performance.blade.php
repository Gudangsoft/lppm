@extends('admin.layouts.app')

@section('title', 'Laporan Kinerja Peneliti')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('admin.internal-grants.reports.index') }}" class="text-primary-600 hover:text-primary-700 text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Laporan Kinerja Peneliti</h1>
    </div>
    <button type="button" onclick="window.print()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
        <i class="fas fa-print mr-2"></i> Cetak
    </button>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.internal-grants.reports.researcher-performance') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIDN..." class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
        </div>
        <div class="w-48">
            <select name="department" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <option value="">Semua Prodi</option>
                @foreach($departments as $dept)
                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
            <i class="fas fa-filter mr-2"></i> Filter
        </button>
    </form>
</div>

<!-- Summary -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-3xl font-bold text-primary-600">{{ $summary['total_researchers'] ?? 0 }}</p>
        <p class="text-sm text-gray-500">Total Peneliti</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-3xl font-bold text-green-600">{{ $summary['with_grants'] ?? 0 }}</p>
        <p class="text-sm text-gray-500">Menerima Hibah</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-3xl font-bold text-blue-600">{{ number_format($summary['avg_grants'] ?? 0, 1) }}</p>
        <p class="text-sm text-gray-500">Rata-rata Hibah/Peneliti</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
        <p class="text-3xl font-bold text-purple-600">{{ number_format($summary['avg_outputs'] ?? 0, 1) }}</p>
        <p class="text-sm text-gray-500">Rata-rata Luaran/Hibah</p>
    </div>
</div>

<!-- Researcher Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peneliti</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prodi</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Pengajuan</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Diterima</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aktif</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Selesai</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Luaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Dana</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($researchers as $researcher)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($researcher->photo)
                            <img src="{{ asset('storage/' . $researcher->photo) }}" class="w-10 h-10 rounded-full object-cover mr-3" alt="">
                            @else
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-900">{{ $researcher->name }}</p>
                                <p class="text-xs text-gray-500">{{ $researcher->nidn }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $researcher->department ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">{{ $researcher->submissions_count ?? 0 }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="font-medium text-green-600">{{ $researcher->accepted_count ?? 0 }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="font-medium text-blue-600">{{ $researcher->active_grants_count ?? 0 }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">{{ $researcher->completed_grants_count ?? 0 }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="font-medium text-purple-600">{{ $researcher->outputs_count ?? 0 }}</span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        Rp {{ number_format($researcher->total_budget ?? 0, 0, ',', '.') }}
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
    @if($researchers->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $researchers->links() }}
    </div>
    @endif
</div>
@endsection
