@extends('admin.layouts.app')

@section('title', 'Buat Pengajuan Hibah')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.internal-grants.submissions.index') }}" class="text-primary-600 hover:text-primary-700">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900">Buat Pengajuan Hibah Internal</h2>
    </div>
    
    <form action="{{ route('admin.internal-grants.submissions.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Pengajuan <span class="text-red-500">*</span></label>
                <select name="period_id" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                    <option value="">Pilih Periode</option>
                    @foreach($periods as $period)
                    <option value="{{ $period->id }}" {{ old('period_id', request('period_id')) == $period->id ? 'selected' : '' }}>
                        {{ $period->scheme->code }} - {{ $period->scheme->name }} ({{ $period->year }})
                    </option>
                    @endforeach
                </select>
                @error('period_id')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ketua Pengusul <span class="text-red-500">*</span></label>
                <select name="researcher_id" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                    <option value="">Pilih Peneliti</option>
                    @foreach($researchers as $researcher)
                    <option value="{{ $researcher->id }}" {{ old('researcher_id') == $researcher->id ? 'selected' : '' }}>
                        {{ $researcher->name }} ({{ $researcher->nidn }})
                    </option>
                    @endforeach
                </select>
                @error('researcher_id')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
            </div>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Judul Penelitian <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
            @error('title')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Abstrak</label>
            <textarea name="abstract" rows="4" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">{{ old('abstract') }}</textarea>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Latar Belakang</label>
            <textarea name="background" rows="4" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">{{ old('background') }}</textarea>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tujuan Penelitian</label>
            <textarea name="objectives" rows="3" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">{{ old('objectives') }}</textarea>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Metodologi</label>
            <textarea name="methodology" rows="4" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">{{ old('methodology') }}</textarea>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Luaran yang Diharapkan</label>
            <textarea name="expected_output" rows="3" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">{{ old('expected_output') }}</textarea>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Anggaran yang Diminta (Rp)</label>
                <input type="number" name="requested_budget" value="{{ old('requested_budget') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" min="0">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">File Proposal (PDF)</label>
                <input type="file" name="proposal_file" accept=".pdf" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
            </div>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="submitted" {{ old('status') == 'submitted' ? 'selected' : '' }}>Diajukan</option>
            </select>
        </div>
        
        <div class="flex justify-end pt-6 border-t border-gray-100">
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                <i class="fas fa-save mr-2"></i>Simpan
            </button>
        </div>
    </form>
</div>
@endsection
