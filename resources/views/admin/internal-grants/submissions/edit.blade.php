@extends('admin.layouts.app')

@section('title', 'Edit Pengajuan')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.internal-grants.submissions.show', $submission) }}" class="text-primary-600 hover:text-primary-700">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-900">Edit Pengajuan: {{ $submission->registration_number }}</h2>
    </div>
    
    <form action="{{ route('admin.internal-grants.submissions.update', $submission) }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Pengajuan <span class="text-red-500">*</span></label>
                <select name="period_id" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                    @foreach($periods as $period)
                    <option value="{{ $period->id }}" {{ old('period_id', $submission->period_id) == $period->id ? 'selected' : '' }}>
                        {{ $period->scheme->code }} - {{ $period->scheme->name }} ({{ $period->year }})
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ketua Pengusul <span class="text-red-500">*</span></label>
                <select name="researcher_id" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                    @foreach($researchers as $researcher)
                    <option value="{{ $researcher->id }}" {{ old('researcher_id', $submission->researcher_id) == $researcher->id ? 'selected' : '' }}>
                        {{ $researcher->name }} ({{ $researcher->nidn }})
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Judul Penelitian <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title', $submission->title) }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Abstrak</label>
            <textarea name="abstract" rows="4" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">{{ old('abstract', $submission->abstract) }}</textarea>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Latar Belakang</label>
            <textarea name="background" rows="4" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">{{ old('background', $submission->background) }}</textarea>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tujuan Penelitian</label>
            <textarea name="objectives" rows="3" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">{{ old('objectives', $submission->objectives) }}</textarea>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Metodologi</label>
            <textarea name="methodology" rows="4" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">{{ old('methodology', $submission->methodology) }}</textarea>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Luaran yang Diharapkan</label>
            <textarea name="expected_output" rows="3" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">{{ old('expected_output', $submission->expected_output) }}</textarea>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Anggaran yang Diminta (Rp)</label>
                <input type="number" name="requested_budget" value="{{ old('requested_budget', $submission->requested_budget) }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" min="0">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">File Proposal (PDF)</label>
                <input type="file" name="proposal_file" accept=".pdf" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                @if($submission->proposal_file)
                <p class="text-sm text-gray-500 mt-1">
                    File saat ini: <a href="{{ asset('storage/' . $submission->proposal_file) }}" target="_blank" class="text-primary-600">Lihat</a>
                </p>
                @endif
            </div>
        </div>
        
        <div class="flex justify-end pt-6 border-t border-gray-100">
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
