@extends('admin.layouts.app')

@section('title', 'Detail Hibah')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.internal-grants.grants.index') }}" class="text-primary-600 hover:text-primary-700">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
    <a href="{{ route('admin.internal-grants.grants.edit', $grant) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
        <i class="fas fa-edit mr-1"></i> Edit
    </a>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Grant Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">{{ $grant->submission->title ?? 'Hibah Internal' }}</h2>
                    <p class="text-sm text-gray-500 font-mono">{{ $grant->contract_number }}</p>
                </div>
                <span class="px-3 py-1 text-sm rounded-full {{ $grant->status_badge }}">{{ $grant->status_label }}</span>
            </div>
            
            <div class="p-6 grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500">Skema</p>
                    <p class="font-medium">{{ $grant->submission->period->scheme->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tahun</p>
                    <p class="font-medium">{{ $grant->submission->period->year ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Anggaran Disetujui</p>
                    <p class="font-medium text-lg text-green-600">Rp {{ number_format($grant->approved_budget ?? 0, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Periode Pelaksanaan</p>
                    <p class="font-medium">{{ $grant->start_date?->format('d M Y') }} - {{ $grant->end_date?->format('d M Y') }}</p>
                </div>
            </div>
            
            @if($grant->contract_file)
            <div class="px-6 pb-6">
                <a href="{{ asset('storage/' . $grant->contract_file) }}" target="_blank" class="inline-flex items-center text-primary-600 hover:text-primary-700">
                    <i class="fas fa-file-pdf mr-2"></i> Download Kontrak
                </a>
            </div>
            @endif
        </div>
        
        <!-- Disbursements -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Pencairan Dana</h3>
                <button type="button" onclick="openDisbursementModal()" class="px-3 py-1.5 bg-primary-600 text-white text-sm rounded-lg hover:bg-primary-700 transition">
                    <i class="fas fa-plus mr-1"></i> Tambah
                </button>
            </div>
            
            @if($grant->disbursements->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($grant->disbursements as $disbursement)
                <div class="px-6 py-4 flex justify-between items-center">
                    <div>
                        <p class="font-medium">{{ $disbursement->description ?? 'Pencairan #' . $loop->iteration }}</p>
                        <p class="text-sm text-gray-500">{{ $disbursement->disbursed_at?->format('d M Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-green-600">Rp {{ number_format($disbursement->amount, 0, ',', '.') }}</p>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $disbursement->status == 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ucfirst($disbursement->status) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                <div class="flex justify-between items-center">
                    <span class="font-medium text-gray-700">Total Dicairkan</span>
                    <span class="font-bold text-lg">Rp {{ number_format($grant->disbursements->sum('amount'), 0, ',', '.') }}</span>
                </div>
                <div class="mt-2">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $grant->disbursement_percentage }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 text-right">{{ $grant->disbursement_percentage }}% dari total anggaran</p>
                </div>
            </div>
            @else
            <div class="px-6 py-12 text-center text-gray-500">
                <i class="fas fa-money-bill-wave text-4xl mb-4 text-gray-300"></i>
                <p>Belum ada pencairan</p>
            </div>
            @endif
        </div>
        
        <!-- Outputs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Luaran</h3>
                <button type="button" onclick="openOutputModal()" class="px-3 py-1.5 bg-primary-600 text-white text-sm rounded-lg hover:bg-primary-700 transition">
                    <i class="fas fa-plus mr-1"></i> Tambah
                </button>
            </div>
            
            @if($grant->outputs->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($grant->outputs as $output)
                <div class="px-6 py-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="px-2 py-0.5 text-xs rounded-full bg-primary-100 text-primary-700 mb-2 inline-block">{{ $output->type_label }}</span>
                            <p class="font-medium">{{ $output->title }}</p>
                            @if($output->description)
                            <p class="text-sm text-gray-600 mt-1">{{ $output->description }}</p>
                            @endif
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $output->status == 'achieved' ? 'bg-green-100 text-green-700' : ($output->status == 'in_progress' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                            {{ ucfirst(str_replace('_', ' ', $output->status)) }}
                        </span>
                    </div>
                    @if($output->url)
                    <a href="{{ $output->url }}" target="_blank" class="text-sm text-primary-600 hover:text-primary-700 mt-2 inline-block">
                        <i class="fas fa-external-link-alt mr-1"></i> Lihat
                    </a>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="px-6 py-12 text-center text-gray-500">
                <i class="fas fa-trophy text-4xl mb-4 text-gray-300"></i>
                <p>Belum ada luaran</p>
            </div>
            @endif
        </div>
        
        <!-- Progress Reports -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Laporan Kemajuan</h3>
                <a href="{{ route('admin.internal-grants.progress-reports.create', ['grant_id' => $grant->id]) }}" class="px-3 py-1.5 bg-primary-600 text-white text-sm rounded-lg hover:bg-primary-700 transition">
                    <i class="fas fa-plus mr-1"></i> Tambah
                </a>
            </div>
            
            @if($grant->progressReports->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($grant->progressReports as $report)
                <div class="px-6 py-4 flex justify-between items-center">
                    <div>
                        <p class="font-medium">Laporan Kemajuan #{{ $loop->iteration }}</p>
                        <p class="text-sm text-gray-500">{{ $report->report_date?->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-primary-600">{{ $report->progress_percentage }}%</p>
                            <p class="text-xs text-gray-500">Progress</p>
                        </div>
                        <a href="{{ route('admin.internal-grants.progress-reports.show', $report) }}" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="px-6 py-12 text-center text-gray-500">
                <i class="fas fa-chart-line text-4xl mb-4 text-gray-300"></i>
                <p>Belum ada laporan kemajuan</p>
            </div>
            @endif
        </div>
        
        <!-- Final Report -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Laporan Akhir</h3>
                @if(!$grant->finalReport)
                <a href="{{ route('admin.internal-grants.final-reports.create', ['grant_id' => $grant->id]) }}" class="px-3 py-1.5 bg-primary-600 text-white text-sm rounded-lg hover:bg-primary-700 transition">
                    <i class="fas fa-plus mr-1"></i> Buat Laporan
                </a>
                @endif
            </div>
            
            @if($grant->finalReport)
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Submit</p>
                        <p class="font-medium">{{ $grant->finalReport->submitted_at?->format('d M Y') }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full {{ $grant->finalReport->status == 'approved' ? 'bg-green-100 text-green-700' : ($grant->finalReport->status == 'revision' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                        {{ ucfirst($grant->finalReport->status) }}
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-500">Realisasi Anggaran</p>
                        <p class="font-medium">Rp {{ number_format($grant->finalReport->budget_realization ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Persentase</p>
                        <p class="font-medium">{{ number_format(($grant->finalReport->budget_realization / $grant->approved_budget) * 100, 1) }}%</p>
                    </div>
                </div>
                
                <a href="{{ route('admin.internal-grants.final-reports.show', $grant->finalReport) }}" class="text-primary-600 hover:text-primary-700">
                    <i class="fas fa-eye mr-1"></i> Lihat Detail
                </a>
            </div>
            @else
            <div class="px-6 py-12 text-center text-gray-500">
                <i class="fas fa-file-alt text-4xl mb-4 text-gray-300"></i>
                <p>Belum ada laporan akhir</p>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Researcher -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Ketua Peneliti</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center">
                    @if($grant->submission->researcher->photo ?? false)
                    <img src="{{ asset('storage/' . $grant->submission->researcher->photo) }}" class="w-12 h-12 rounded-full object-cover mr-4" alt="">
                    @else
                    <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    @endif
                    <div>
                        <p class="font-medium text-gray-900">{{ $grant->submission->researcher->name ?? '-' }}</p>
                        <p class="text-sm text-gray-500">{{ $grant->submission->researcher->nidn ?? '' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Timeline -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Timeline</h3>
            </div>
            <div class="p-6">
                <div class="relative">
                    <div class="absolute left-2 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-4 h-4 rounded-full bg-green-500 border-2 border-white z-10"></div>
                            <div class="ml-4">
                                <p class="text-sm font-medium">Kontrak Ditandatangani</p>
                                <p class="text-xs text-gray-500">{{ $grant->contract_date?->format('d M Y') ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-4 h-4 rounded-full {{ $grant->start_date && $grant->start_date->isPast() ? 'bg-blue-500' : 'bg-gray-300' }} border-2 border-white z-10"></div>
                            <div class="ml-4">
                                <p class="text-sm font-medium">Mulai Penelitian</p>
                                <p class="text-xs text-gray-500">{{ $grant->start_date?->format('d M Y') ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-4 h-4 rounded-full {{ $grant->end_date && $grant->end_date->isPast() ? 'bg-blue-500' : 'bg-gray-300' }} border-2 border-white z-10"></div>
                            <div class="ml-4">
                                <p class="text-sm font-medium">Selesai Penelitian</p>
                                <p class="text-xs text-gray-500">{{ $grant->end_date?->format('d M Y') ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-4 h-4 rounded-full {{ $grant->finalReport ? 'bg-green-500' : 'bg-gray-300' }} border-2 border-white z-10"></div>
                            <div class="ml-4">
                                <p class="text-sm font-medium">Laporan Akhir</p>
                                <p class="text-xs text-gray-500">{{ $grant->finalReport?->submitted_at?->format('d M Y') ?? 'Belum' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Links -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Link Cepat</h3>
            </div>
            <div class="p-4 space-y-2">
                <a href="{{ route('admin.internal-grants.submissions.show', $grant->submission) }}" class="flex items-center p-3 text-gray-700 hover:bg-gray-50 rounded-lg">
                    <i class="fas fa-file-alt mr-3 text-gray-400"></i> Lihat Pengajuan
                </a>
                <a href="{{ route('admin.internal-grants.reports.active-grants') }}" class="flex items-center p-3 text-gray-700 hover:bg-gray-50 rounded-lg">
                    <i class="fas fa-chart-bar mr-3 text-gray-400"></i> Laporan Hibah Aktif
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Disbursement Modal -->
<div id="disbursementModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Tambah Pencairan</h3>
            <button type="button" onclick="closeDisbursementModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('admin.internal-grants.grants.add-disbursement', $grant) }}" method="POST" class="p-6">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah (Rp)</label>
                <input type="number" name="amount" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pencairan</label>
                <input type="date" name="disbursed_at" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                <input type="text" name="description" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                Simpan
            </button>
        </form>
    </div>
</div>

<!-- Output Modal -->
<div id="outputModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Tambah Luaran</h3>
            <button type="button" onclick="closeOutputModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('admin.internal-grants.grants.add-output', $grant) }}" method="POST" class="p-6">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Luaran</label>
                <select name="type" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
                    <option value="publication">Publikasi Jurnal</option>
                    <option value="proceeding">Prosiding</option>
                    <option value="book">Buku</option>
                    <option value="hki">HKI</option>
                    <option value="prototype">Prototipe</option>
                    <option value="other">Lainnya</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Judul</label>
                <input type="text" name="title" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">URL</label>
                <input type="url" name="url" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    <option value="planned">Direncanakan</option>
                    <option value="in_progress">Dalam Proses</option>
                    <option value="achieved">Tercapai</option>
                </select>
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                Simpan
            </button>
        </form>
    </div>
</div>

<script>
function openDisbursementModal() { document.getElementById('disbursementModal').classList.remove('hidden'); }
function closeDisbursementModal() { document.getElementById('disbursementModal').classList.add('hidden'); }
function openOutputModal() { document.getElementById('outputModal').classList.remove('hidden'); }
function closeOutputModal() { document.getElementById('outputModal').classList.add('hidden'); }
</script>
@endsection
