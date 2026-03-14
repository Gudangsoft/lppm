@extends('admin.layouts.app')

@section('title', 'Detail Pengajuan')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.internal-grants.submissions.index') }}" class="text-primary-600 hover:text-primary-700">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
    <div class="flex space-x-2">
        <a href="{{ route('admin.internal-grants.submissions.edit', $submission) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
            <i class="fas fa-edit mr-1"></i> Edit
        </a>
        <a href="{{ route('admin.internal-grants.submissions.review', $submission) }}" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 text-sm">
            <i class="fas fa-star mr-1"></i> Review
        </a>
    </div>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Basic Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">{{ $submission->title }}</h2>
                    <p class="text-sm text-gray-500 font-mono">{{ $submission->registration_number }}</p>
                </div>
                <span class="px-3 py-1 text-sm rounded-full {{ $submission->status_badge }}">{{ $submission->status_label }}</span>
            </div>
            
            <div class="p-6 space-y-6">
                @if($submission->abstract)
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Abstrak</h3>
                    <p class="text-gray-600 whitespace-pre-line">{{ $submission->abstract }}</p>
                </div>
                @endif
                
                @if($submission->background)
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Latar Belakang</h3>
                    <p class="text-gray-600 whitespace-pre-line">{{ $submission->background }}</p>
                </div>
                @endif
                
                @if($submission->objectives)
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Tujuan</h3>
                    <p class="text-gray-600 whitespace-pre-line">{{ $submission->objectives }}</p>
                </div>
                @endif
                
                @if($submission->methodology)
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Metodologi</h3>
                    <p class="text-gray-600 whitespace-pre-line">{{ $submission->methodology }}</p>
                </div>
                @endif
                
                @if($submission->expected_output)
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Luaran yang Diharapkan</h3>
                    <p class="text-gray-600 whitespace-pre-line">{{ $submission->expected_output }}</p>
                </div>
                @endif
                
                @if($submission->proposal_file)
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">File Proposal</h3>
                    <a href="{{ asset('storage/' . $submission->proposal_file) }}" target="_blank" class="inline-flex items-center text-primary-600 hover:text-primary-700">
                        <i class="fas fa-file-pdf mr-2"></i> Download Proposal
                    </a>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Reviews -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Review</h3>
            </div>
            
            @if($submission->reviews->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($submission->reviews as $review)
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <p class="font-medium text-gray-900">{{ $review->reviewer->name }}</p>
                            <p class="text-sm text-gray-500">{{ $review->reviewed_at?->format('d M Y H:i') ?? 'Belum direview' }}</p>
                        </div>
                        @if($review->recommendation)
                        <span class="px-3 py-1 text-sm rounded-full {{ $review->recommendation_badge }}">
                            {{ ucfirst($review->recommendation) }}
                        </span>
                        @endif
                    </div>
                    
                    @if($review->total_score)
                    <div class="grid grid-cols-5 gap-4 mb-4">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-primary-600">{{ $review->score_relevance }}</p>
                            <p class="text-xs text-gray-500">Relevansi</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-primary-600">{{ $review->score_methodology }}</p>
                            <p class="text-xs text-gray-500">Metodologi</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-primary-600">{{ $review->score_output }}</p>
                            <p class="text-xs text-gray-500">Luaran</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-primary-600">{{ $review->score_budget }}</p>
                            <p class="text-xs text-gray-500">Anggaran</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-primary-600">{{ $review->score_team }}</p>
                            <p class="text-xs text-gray-500">Tim</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-3 text-center mb-4">
                        <p class="text-sm text-gray-500">Total Skor</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $review->total_score }}</p>
                    </div>
                    @endif
                    
                    @if($review->comments)
                    <div class="mb-3">
                        <p class="text-sm font-medium text-gray-700">Komentar:</p>
                        <p class="text-gray-600">{{ $review->comments }}</p>
                    </div>
                    @endif
                    
                    @if($review->suggestions)
                    <div>
                        <p class="text-sm font-medium text-gray-700">Saran:</p>
                        <p class="text-gray-600">{{ $review->suggestions }}</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="px-6 py-12 text-center text-gray-500">
                <i class="fas fa-star text-4xl mb-4 text-gray-300"></i>
                <p>Belum ada review</p>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Status & Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Status</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.internal-grants.submissions.update-status', $submission) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500 mb-4">
                        <option value="draft" {{ $submission->status == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="submitted" {{ $submission->status == 'submitted' ? 'selected' : '' }}>Diajukan</option>
                        <option value="under_review" {{ $submission->status == 'under_review' ? 'selected' : '' }}>Sedang Direview</option>
                        <option value="revision" {{ $submission->status == 'revision' ? 'selected' : '' }}>Revisi</option>
                        <option value="accepted" {{ $submission->status == 'accepted' ? 'selected' : '' }}>Diterima</option>
                        <option value="rejected" {{ $submission->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <button type="submit" class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                        Update Status
                    </button>
                </form>
                
                @if($submission->grant)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.internal-grants.grants.show', $submission->grant) }}" class="flex items-center justify-center w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-file-contract mr-2"></i> Lihat Kontrak
                    </a>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Informasi</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Skema</p>
                    <p class="font-medium">{{ $submission->period->scheme->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Periode</p>
                    <p class="font-medium">{{ $submission->period->year ?? '-' }} {{ $submission->period->semester ?? '' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Anggaran Diminta</p>
                    <p class="font-medium">Rp {{ number_format($submission->requested_budget ?? 0, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tanggal Diajukan</p>
                    <p class="font-medium">{{ $submission->submitted_at?->format('d M Y H:i') ?? '-' }}</p>
                </div>
                @if($submission->average_score)
                <div>
                    <p class="text-sm text-gray-500">Rata-rata Skor</p>
                    <p class="font-medium text-xl text-primary-600">{{ $submission->average_score }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Researcher -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Ketua Pengusul</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center">
                    @if($submission->researcher->photo)
                    <img src="{{ asset('storage/' . $submission->researcher->photo) }}" class="w-12 h-12 rounded-full object-cover mr-4" alt="">
                    @else
                    <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    @endif
                    <div>
                        <p class="font-medium text-gray-900">{{ $submission->researcher->name }}</p>
                        <p class="text-sm text-gray-500">{{ $submission->researcher->nidn }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 space-y-2">
                    <p class="text-sm"><span class="text-gray-500">Prodi:</span> {{ $submission->researcher->department ?? '-' }}</p>
                    <p class="text-sm"><span class="text-gray-500">Email:</span> {{ $submission->researcher->email ?? '-' }}</p>
                </div>
            </div>
        </div>
        
        <!-- Team -->
        @if($submission->team->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Anggota Tim</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($submission->team as $member)
                <div class="px-6 py-3 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900">{{ $member->name }}</p>
                        <p class="text-xs text-gray-500">{{ $member->nidn }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">{{ ucfirst($member->pivot->role) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
