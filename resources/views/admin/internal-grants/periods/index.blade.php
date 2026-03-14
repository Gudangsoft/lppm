@extends('admin.layouts.app')

@section('title', 'Periode Pengajuan Hibah')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Periode Pengajuan</h1>
        <p class="text-gray-600">Kelola periode pengajuan hibah internal</p>
    </div>
    <a href="{{ route('admin.internal-grants.periods.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
        <i class="fas fa-plus mr-2"></i>
        Tambah Periode
    </a>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.internal-grants.periods.index') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="w-48">
            <select name="scheme" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <option value="">Semua Skema</option>
                @foreach($schemes as $scheme)
                <option value="{{ $scheme->id }}" {{ request('scheme') == $scheme->id ? 'selected' : '' }}>{{ $scheme->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-32">
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
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Dibuka</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Ditutup</option>
                <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
                <option value="announced" {{ request('status') == 'announced' ? 'selected' : '' }}>Diumumkan</option>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skema</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode Pengajuan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengajuan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($periods as $period)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $period->scheme->name ?? '-' }}</div>
                        <div class="text-sm text-gray-500">{{ $period->scheme->code ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 font-medium">{{ $period->year }} {{ $period->semester }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm">{{ $period->submission_start->format('d M Y') }}</div>
                        <div class="text-sm text-gray-500">s/d {{ $period->submission_end->format('d M Y') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($period->total_budget_available)
                        Rp {{ number_format($period->total_budget_available, 0, ',', '.') }}
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-medium">{{ $period->submissions->count() }}</span>
                        @if($period->max_proposals)
                        <span class="text-gray-500">/ {{ $period->max_proposals }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $period->status_badge }}">
                            {{ ucfirst($period->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.internal-grants.periods.show', $period) }}" class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.internal-grants.periods.edit', $period) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.internal-grants.periods.destroy', $period) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus periode ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-calendar-alt text-4xl mb-4 text-gray-300"></i>
                        <p>Belum ada periode pengajuan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($periods->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $periods->links() }}
    </div>
    @endif
</div>
@endsection
