@extends('admin.layouts.app')

@section('title', 'Hibah Internal Aktif')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Hibah Internal Aktif</h1>
        <p class="text-gray-600">Kelola hibah yang sudah diterima</p>
    </div>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-lg bg-primary-100 flex items-center justify-center mr-4">
                <i class="fas fa-file-contract text-primary-600 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">Total Hibah</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
                <i class="fas fa-spinner text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">Sedang Berjalan</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center mr-4">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['completed'] ?? 0 }}</p>
                <p class="text-sm text-gray-500">Selesai</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-lg bg-yellow-100 flex items-center justify-center mr-4">
                <i class="fas fa-money-bill-wave text-yellow-600 text-xl"></i>
            </div>
            <div>
                <p class="text-xl font-bold text-gray-900">Rp {{ number_format($stats['total_budget'] ?? 0, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-500">Total Anggaran</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.internal-grants.grants.index') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau no. kontrak..." class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
        </div>
        <div class="w-40">
            <select name="year" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <option value="">Semua Tahun</option>
                @foreach($years as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-40">
            <select name="status" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Dihentikan</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
            <i class="fas fa-search mr-2"></i> Filter
        </button>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Kontrak</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skema</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ketua</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pencairan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($grants as $grant)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-mono text-sm">{{ $grant->contract_number }}</td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-900 line-clamp-2">{{ $grant->submission->title ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs bg-primary-100 text-primary-700 rounded-full">{{ $grant->submission->period->scheme->code ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">{{ $grant->submission->researcher->name ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        Rp {{ number_format($grant->approved_budget ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $grant->disbursement_percentage }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $grant->disbursement_percentage }}%</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $grant->status_badge }}">{{ $grant->status_label }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.internal-grants.grants.show', $grant) }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.internal-grants.grants.edit', $grant) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-file-contract text-4xl mb-4 text-gray-300"></i>
                        <p>Belum ada hibah aktif</p>
                    </td>
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
