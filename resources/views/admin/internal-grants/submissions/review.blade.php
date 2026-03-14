@extends('admin.layouts.app')

@section('title', 'Review Pengajuan')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.internal-grants.submissions.show', $submission) }}" class="text-primary-600 hover:text-primary-700">
        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Detail
    </a>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Proposal Summary -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden sticky top-4">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Ringkasan Proposal</h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-sm text-gray-500">No. Registrasi</p>
                    <p class="font-mono text-sm">{{ $submission->registration_number }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Judul</p>
                    <p class="font-medium">{{ $submission->title }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Ketua Pengusul</p>
                    <p class="font-medium">{{ $submission->researcher->name }}</p>
                    <p class="text-xs text-gray-500">{{ $submission->researcher->nidn }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Skema</p>
                    <p class="font-medium">{{ $submission->period->scheme->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Anggaran Diminta</p>
                    <p class="font-medium">Rp {{ number_format($submission->requested_budget ?? 0, 0, ',', '.') }}</p>
                </div>
                
                @if($submission->proposal_file)
                <div class="pt-4 border-t border-gray-100">
                    <a href="{{ asset('storage/' . $submission->proposal_file) }}" target="_blank" class="inline-flex items-center w-full justify-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        <i class="fas fa-file-pdf mr-2"></i> Download Proposal
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Review Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Form Review</h2>
            </div>
            
            <form action="{{ route('admin.internal-grants.submissions.store-review', $submission) }}" method="POST" class="p-6">
                @csrf
                
                <!-- Scoring -->
                <div class="mb-8">
                    <h3 class="text-md font-semibold text-gray-800 mb-4">Penilaian (0-100)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Relevansi & Urgensi <span class="text-red-500">*</span></label>
                            <input type="number" name="score_relevance" value="{{ old('score_relevance') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" min="0" max="100" required>
                            <p class="text-xs text-gray-500 mt-1">Kesesuaian dengan roadmap & kebutuhan</p>
                            @error('score_relevance')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Metodologi <span class="text-red-500">*</span></label>
                            <input type="number" name="score_methodology" value="{{ old('score_methodology') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" min="0" max="100" required>
                            <p class="text-xs text-gray-500 mt-1">Ketepatan metode & pendekatan</p>
                            @error('score_methodology')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Luaran <span class="text-red-500">*</span></label>
                            <input type="number" name="score_output" value="{{ old('score_output') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" min="0" max="100" required>
                            <p class="text-xs text-gray-500 mt-1">Kesesuaian & capaian luaran</p>
                            @error('score_output')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Anggaran <span class="text-red-500">*</span></label>
                            <input type="number" name="score_budget" value="{{ old('score_budget') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" min="0" max="100" required>
                            <p class="text-xs text-gray-500 mt-1">Kewajaran & rincian anggaran</p>
                            @error('score_budget')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tim Peneliti <span class="text-red-500">*</span></label>
                            <input type="number" name="score_team" value="{{ old('score_team') }}" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" min="0" max="100" required>
                            <p class="text-xs text-gray-500 mt-1">Kompetensi & rekam jejak tim</p>
                            @error('score_team')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
                
                <!-- Comments -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Komentar</label>
                    <textarea name="comments" rows="4" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">{{ old('comments') }}</textarea>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Saran Perbaikan</label>
                    <textarea name="suggestions" rows="4" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">{{ old('suggestions') }}</textarea>
                </div>
                
                <!-- Recommendation -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rekomendasi <span class="text-red-500">*</span></label>
                    <div class="flex space-x-4">
                        <label class="flex items-center px-4 py-3 border rounded-lg cursor-pointer hover:bg-green-50 @if(old('recommendation') == 'accept') border-green-500 bg-green-50 @else border-gray-200 @endif">
                            <input type="radio" name="recommendation" value="accept" {{ old('recommendation') == 'accept' ? 'checked' : '' }} class="text-green-600 focus:ring-green-500" required>
                            <span class="ml-2 font-medium text-green-700">Diterima</span>
                        </label>
                        <label class="flex items-center px-4 py-3 border rounded-lg cursor-pointer hover:bg-yellow-50 @if(old('recommendation') == 'revision') border-yellow-500 bg-yellow-50 @else border-gray-200 @endif">
                            <input type="radio" name="recommendation" value="revision" {{ old('recommendation') == 'revision' ? 'checked' : '' }} class="text-yellow-600 focus:ring-yellow-500">
                            <span class="ml-2 font-medium text-yellow-700">Revisi</span>
                        </label>
                        <label class="flex items-center px-4 py-3 border rounded-lg cursor-pointer hover:bg-red-50 @if(old('recommendation') == 'reject') border-red-500 bg-red-50 @else border-gray-200 @endif">
                            <input type="radio" name="recommendation" value="reject" {{ old('recommendation') == 'reject' ? 'checked' : '' }} class="text-red-600 focus:ring-red-500">
                            <span class="ml-2 font-medium text-red-700">Ditolak</span>
                        </label>
                    </div>
                    @error('recommendation')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                </div>
                
                <div class="flex justify-end pt-6 border-t border-gray-100">
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                        <i class="fas fa-save mr-2"></i>Simpan Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
