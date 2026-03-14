@extends('admin.layouts.app')

@section('title', 'Tambah Periode Pengajuan')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.internal-grants.periods.index') }}" class="text-primary-600 hover:text-primary-700">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900">Tambah Periode Pengajuan</h2>
    </div>
    
    <form action="{{ route('admin.internal-grants.periods.store') }}" method="POST" class="p-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Skema Hibah <span class="text-red-500">*</span></label>
                <select name="scheme_id" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                    <option value="">Pilih Skema</option>
                    @foreach($schemes as $scheme)
                    <option value="{{ $scheme->id }}" {{ old('scheme_id') == $scheme->id ? 'selected' : '' }}>{{ $scheme->name }} ({{ $scheme->code }})</option>
                    @endforeach
                </select>
                @error('scheme_id')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun <span class="text-red-500">*</span></label>
                    <input type="text" name="year" value="{{ old('year', date('Y')) }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" maxlength="4" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                    <select name="semester" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                        <option value="">-</option>
                        <option value="Ganjil" {{ old('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="Genap" {{ old('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai Pengajuan <span class="text-red-500">*</span></label>
                <input type="date" name="submission_start" value="{{ old('submission_start') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                @error('submission_start')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir Pengajuan <span class="text-red-500">*</span></label>
                <input type="date" name="submission_end" value="{{ old('submission_end') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                @error('submission_end')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai Review</label>
                <input type="date" name="review_start" value="{{ old('review_start') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir Review</label>
                <input type="date" name="review_end" value="{{ old('review_end') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pengumuman</label>
                <input type="date" name="announcement_date" value="{{ old('announcement_date') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Total Anggaran Tersedia (Rp)</label>
                <input type="number" name="total_budget_available" value="{{ old('total_budget_available') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" min="0">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Maksimal Proposal</label>
                <input type="number" name="max_proposals" value="{{ old('max_proposals') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" min="0">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Dibuka</option>
                    <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Ditutup</option>
                </select>
            </div>
        </div>
        
        <div class="flex justify-end pt-6 mt-6 border-t border-gray-100">
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                <i class="fas fa-save mr-2"></i>Simpan
            </button>
        </div>
    </form>
</div>
@endsection
