@extends('admin.layouts.app')

@section('title', 'Laporan Hibah Internal')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Laporan Hibah Internal</h1>
    <p class="text-gray-600">Dashboard pelaporan seperti BIMA</p>
</div>

<!-- Report Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <a href="{{ route('admin.internal-grants.reports.submissions') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition group">
        <div class="flex items-center mb-4">
            <div class="w-14 h-14 rounded-lg bg-blue-100 flex items-center justify-center mr-4 group-hover:bg-blue-200 transition">
                <i class="fas fa-file-alt text-blue-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Laporan Pengajuan</h3>
                <p class="text-sm text-gray-500">Rekap pengajuan hibah</p>
            </div>
        </div>
        <p class="text-gray-600 text-sm">Lihat statistik pengajuan hibah internal per periode, skema, dan status.</p>
    </a>
    
    <a href="{{ route('admin.internal-grants.reports.active-grants') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition group">
        <div class="flex items-center mb-4">
            <div class="w-14 h-14 rounded-lg bg-green-100 flex items-center justify-center mr-4 group-hover:bg-green-200 transition">
                <i class="fas fa-clipboard-check text-green-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Hibah Aktif</h3>
                <p class="text-sm text-gray-500">Monitoring pelaksanaan</p>
            </div>
        </div>
        <p class="text-gray-600 text-sm">Monitor hibah yang sedang berjalan, progress, dan status pelaporan.</p>
    </a>
    
    <a href="{{ route('admin.internal-grants.reports.budget-realization') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition group">
        <div class="flex items-center mb-4">
            <div class="w-14 h-14 rounded-lg bg-yellow-100 flex items-center justify-center mr-4 group-hover:bg-yellow-200 transition">
                <i class="fas fa-money-check-alt text-yellow-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Realisasi Anggaran</h3>
                <p class="text-sm text-gray-500">Pencairan & penggunaan dana</p>
            </div>
        </div>
        <p class="text-gray-600 text-sm">Laporan pencairan dana dan realisasi penggunaan anggaran hibah.</p>
    </a>
    
    <a href="{{ route('admin.internal-grants.reports.outputs') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition group">
        <div class="flex items-center mb-4">
            <div class="w-14 h-14 rounded-lg bg-purple-100 flex items-center justify-center mr-4 group-hover:bg-purple-200 transition">
                <i class="fas fa-trophy text-purple-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Capaian Luaran</h3>
                <p class="text-sm text-gray-500">Publikasi, HKI, Prototipe</p>
            </div>
        </div>
        <p class="text-gray-600 text-sm">Rekap luaran riset: publikasi jurnal, prosiding, HKI, buku, dan prototipe.</p>
    </a>
    
    <a href="{{ route('admin.internal-grants.reports.researcher-performance') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition group">
        <div class="flex items-center mb-4">
            <div class="w-14 h-14 rounded-lg bg-red-100 flex items-center justify-center mr-4 group-hover:bg-red-200 transition">
                <i class="fas fa-users text-red-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Kinerja Peneliti</h3>
                <p class="text-sm text-gray-500">Rekam jejak peneliti</p>
            </div>
        </div>
        <p class="text-gray-600 text-sm">Evaluasi kinerja peneliti berdasarkan hibah yang diterima dan capaiannya.</p>
    </a>
    
    <a href="{{ route('admin.internal-grants.reports.export') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition group">
        <div class="flex items-center mb-4">
            <div class="w-14 h-14 rounded-lg bg-gray-100 flex items-center justify-center mr-4 group-hover:bg-gray-200 transition">
                <i class="fas fa-download text-gray-600 text-2xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Ekspor Data</h3>
                <p class="text-sm text-gray-500">Download laporan</p>
            </div>
        </div>
        <p class="text-gray-600 text-sm">Ekspor data hibah dalam format Excel untuk pelaporan eksternal.</p>
    </a>
</div>

<!-- Quick Stats -->
<div class="mt-8">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Cepat</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
            <p class="text-3xl font-bold text-primary-600">{{ $stats['total_submissions'] ?? 0 }}</p>
            <p class="text-sm text-gray-500">Total Pengajuan</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
            <p class="text-3xl font-bold text-green-600">{{ $stats['accepted_submissions'] ?? 0 }}</p>
            <p class="text-sm text-gray-500">Diterima</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
            <p class="text-3xl font-bold text-blue-600">{{ $stats['active_grants'] ?? 0 }}</p>
            <p class="text-sm text-gray-500">Hibah Aktif</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
            <p class="text-3xl font-bold text-purple-600">{{ $stats['total_outputs'] ?? 0 }}</p>
            <p class="text-sm text-gray-500">Total Luaran</p>
        </div>
    </div>
</div>
@endsection
