@extends('admin.layouts.app')

@section('title', 'Detail Periode Pengajuan')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.internal-grants.periods.index') }}" class="text-primary-600 hover:text-primary-700">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

<!-- Period Info -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-900">{{ $period->scheme->name }} - {{ $period->year }}</h2>
        <span class="px-3 py-1 text-sm rounded-full {{ $period->status_badge }}">{{ ucfirst($period->status) }}</span>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-500">Skema</p>
                <p class="font-medium">{{ $period->scheme->code }} - {{ $period->scheme->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Periode Pengajuan</p>
                <p class="font-medium">{{ $period->submission_start->format('d M Y') }} - {{ $period->submission_end->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Anggaran</p>
                <p class="font-medium">Rp {{ number_format($period->total_budget_available ?? 0, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Periode Review</p>
                <p class="font-medium">
                    @if($period->review_start && $period->review_end)
                    {{ $period->review_start->format('d M Y') }} - {{ $period->review_end->format('d M Y') }}
                    @else
                    <span class="text-gray-400">Belum diatur</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Tanggal Pengumuman</p>
                <p class="font-medium">
                    @if($period->announcement_date)
                    {{ $period->announcement_date->format('d M Y') }}
                    @else
                    <span class="text-gray-400">Belum diatur</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Jumlah Pengajuan</p>
                <p class="font-medium">{{ $period->submissions->count() }} proposal</p>
            </div>
        </div>
        
        <!-- Update Status -->
        <div class="mt-6 pt-6 border-t border-gray-100">
            <form action="{{ route('admin.internal-grants.periods.update-status', $period) }}" method="POST" class="flex items-center gap-4">
                @csrf
                @method('PATCH')
                <label class="text-sm font-medium text-gray-700">Ubah Status:</label>
                <select name="status" class="rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    <option value="draft" {{ $period->status == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="open" {{ $period->status == 'open' ? 'selected' : '' }}>Dibuka</option>
                    <option value="closed" {{ $period->status == 'closed' ? 'selected' : '' }}>Ditutup</option>
                    <option value="review" {{ $period->status == 'review' ? 'selected' : '' }}>Review</option>
                    <option value="announced" {{ $period->status == 'announced' ? 'selected' : '' }}>Diumumkan</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 text-sm">
                    Update Status
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-sm text-gray-500">Draft</p>
        <p class="text-2xl font-bold text-gray-600">{{ $period->submissions->where('status', 'draft')->count() }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-sm text-gray-500">Diajukan</p>
        <p class="text-2xl font-bold text-blue-600">{{ $period->submissions->where('status', 'submitted')->count() }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-sm text-gray-500">Direview</p>
        <p class="text-2xl font-bold text-yellow-600">{{ $period->submissions->where('status', 'under_review')->count() }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-sm text-gray-500">Diterima</p>
        <p class="text-2xl font-bold text-green-600">{{ $period->submissions->where('status', 'accepted')->count() }}</p>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <p class="text-sm text-gray-500">Ditolak</p>
        <p class="text-2xl font-bold text-red-600">{{ $period->submissions->where('status', 'rejected')->count() }}</p>
    </div>
</div>

<!-- Submissions List -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900">Daftar Pengajuan</h3>
        <a href="{{ route('admin.internal-grants.submissions.create', ['period_id' => $period->id]) }}" class="text-sm text-primary-600 hover:text-primary-700">
            <i class="fas fa-plus mr-1"></i> Tambah Pengajuan
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Registrasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ketua Pengusul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($period->submissions as $submission)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-mono text-sm">{{ $submission->registration_number }}</td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-900 line-clamp-1">{{ $submission->title }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">{{ $submission->researcher->name }}</p>
                        <p class="text-xs text-gray-500">{{ $submission->researcher->nidn }}</p>
                    </td>
                    <td class="px-6 py-4">Rp {{ number_format($submission->requested_budget ?? 0, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        @if($submission->average_score)
                        <span class="font-medium">{{ $submission->average_score }}</span>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $submission->status_badge }}">{{ $submission->status_label }}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.internal-grants.submissions.show', $submission) }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.internal-grants.submissions.review', $submission) }}" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg" title="Review">
                                <i class="fas fa-star"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-file-alt text-4xl mb-4 text-gray-300"></i>
                        <p>Belum ada pengajuan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
