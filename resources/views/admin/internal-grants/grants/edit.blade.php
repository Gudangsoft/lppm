@extends('admin.layouts.app')

@section('title', 'Edit Hibah')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.internal-grants.grants.show', $grant) }}" class="text-primary-600 hover:text-primary-700">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900">Edit Hibah: {{ $grant->contract_number }}</h2>
    </div>
    
    <form action="{{ route('admin.internal-grants.grants.update', $grant) }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">No. Kontrak</label>
                <input type="text" name="contract_number" value="{{ old('contract_number', $grant->contract_number) }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kontrak</label>
                <input type="date" name="contract_date" value="{{ old('contract_date', $grant->contract_date?->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Anggaran Disetujui (Rp)</label>
                <input type="number" name="approved_budget" value="{{ old('approved_budget', $grant->approved_budget) }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ old('start_date', $grant->start_date?->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ old('end_date', $grant->end_date?->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    <option value="active" {{ old('status', $grant->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="completed" {{ old('status', $grant->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="terminated" {{ old('status', $grant->status) == 'terminated' ? 'selected' : '' }}>Dihentikan</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">File Kontrak (PDF)</label>
                <input type="file" name="contract_file" accept=".pdf" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                @if($grant->contract_file)
                <p class="text-sm text-gray-500 mt-1">
                    File saat ini: <a href="{{ asset('storage/' . $grant->contract_file) }}" target="_blank" class="text-primary-600">Lihat</a>
                </p>
                @endif
            </div>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
            <textarea name="notes" rows="3" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">{{ old('notes', $grant->notes) }}</textarea>
        </div>
        
        <div class="flex justify-end pt-6 border-t border-gray-100">
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
