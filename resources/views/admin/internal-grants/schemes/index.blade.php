@extends('admin.layouts.app')

@section('title', 'Skema Hibah Internal')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Skema Hibah Internal</h1>
        <p class="text-gray-600">Kelola jenis-jenis hibah internal</p>
    </div>
    <a href="{{ route('admin.internal-grants.schemes.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
        <i class="fas fa-plus mr-2"></i>
        Tambah Skema
    </a>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Skema</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Maks. Anggaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($schemes as $scheme)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $scheme->code }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $scheme->name }}</div>
                        @if($scheme->description)
                        <div class="text-sm text-gray-500">{{ Str::limit($scheme->description, 50) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($scheme->max_budget)
                        Rp {{ number_format($scheme->max_budget, 0, ',', '.') }}
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $scheme->max_duration_months }} bulan</td>
                    <td class="px-6 py-4">
                        @if($scheme->is_active)
                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Aktif</span>
                        @else
                        <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">Non-aktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-gray-600">{{ $scheme->periods_count }} periode</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.internal-grants.schemes.edit', $scheme) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.internal-grants.schemes.destroy', $scheme) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus skema ini?')">
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
                        <i class="fas fa-folder-open text-4xl mb-4 text-gray-300"></i>
                        <p>Belum ada skema hibah</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($schemes->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $schemes->links() }}
    </div>
    @endif
</div>
@endsection
