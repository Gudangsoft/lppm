@extends('admin.layouts.app')

@section('title', 'Pengajuan Hibah Internal')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pengajuan Hibah Internal</h1>
        <p class="text-gray-600">Kelola pengajuan hibah dari peneliti</p>
    </div>
    <a href="{{ route('admin.internal-grants.submissions.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
        <i class="fas fa-plus mr-2"></i>
        Buat Pengajuan
    </a>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.internal-grants.submissions.index') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau no. registrasi..." class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
        </div>
        <div class="w-56">
            <select name="period" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <option value="">Semua Periode</option>
                @foreach($periods as $period)
                <option value="{{ $period->id }}" {{ request('period') == $period->id ? 'selected' : '' }}>{{ $period->scheme->code }} - {{ $period->year }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-40">
            <select name="status" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <option value="">Semua Status</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Diajukan</option>
                <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Direview</option>
                <option value="revision" {{ request('status') == 'revision' ? 'selected' : '' }}>Revisi</option>
                <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Diterima</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Registrasi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skema / Periode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pengusul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Anggaran</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($submissions as $submission)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-mono text-sm">{{ $submission->registration_number }}</td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-900 line-clamp-2">{{ $submission->title }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs bg-primary-100 text-primary-700 rounded-full">{{ $submission->period->scheme->code ?? '-' }}</span>
                        <span class="text-sm text-gray-600 block mt-1">{{ $submission->period->year ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">{{ $submission->researcher->name ?? '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $submission->researcher->nidn ?? '' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        Rp {{ number_format($submission->requested_budget ?? 0, 0, ',', '.') }}
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
                            <a href="{{ route('admin.internal-grants.submissions.edit', $submission) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Edit">
                                <i class="fas fa-edit"></i>
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
    
    @if($submissions->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $submissions->links() }}
    </div>
    @endif
</div>
@endsection
