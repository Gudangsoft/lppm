@extends('admin.layouts.app')

@section('title', 'Backup Database')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Backup Database</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola backup database aplikasi LPPM.</p>
        </div>
        <form method="POST" action="{{ route('admin.backup.create') }}"
              onsubmit="return confirmBackup(this)">
            @csrf
            <button type="submit"
                    id="btn-backup"
                    class="inline-flex items-center px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-lg shadow transition-colors duration-200">
                <i class="fas fa-database mr-2"></i>
                Buat Backup Sekarang
            </button>
        </form>
    </div>

    {{-- Info Card --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex gap-3">
        <i class="fas fa-info-circle text-blue-500 text-lg mt-0.5 flex-shrink-0"></i>
        <div class="text-sm text-blue-700">
            <p class="font-semibold mb-1">Tentang Backup Database</p>
            <ul class="list-disc list-inside space-y-1 text-blue-600">
                <li>Backup menggunakan <code class="bg-blue-100 px-1 rounded">mysqldump</code> dan dikompres menjadi file <code class="bg-blue-100 px-1 rounded">.zip</code>.</li>
                <li>Pastikan <code class="bg-blue-100 px-1 rounded">mysqldump</code> sudah tersedia di PATH server.</li>
                <li>File backup disimpan di <code class="bg-blue-100 px-1 rounded">storage/app/backups/</code>.</li>
                <li>Hapus backup lama secara berkala untuk menghemat ruang disk.</li>
            </ul>
        </div>
    </div>

    {{-- Backup List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-base font-semibold text-gray-900">
                <i class="fas fa-archive mr-2 text-primary-600"></i>
                Daftar File Backup
            </h2>
            <span class="text-sm text-gray-500">{{ count($backups) }} file</span>
        </div>

        @if(count($backups) === 0)
            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                <i class="fas fa-box-open text-5xl mb-4"></i>
                <p class="text-base font-medium">Belum ada file backup</p>
                <p class="text-sm mt-1">Klik tombol <strong>"Buat Backup Sekarang"</strong> untuk membuat backup pertama.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama File</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ukuran</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Backup</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($backups as $index => $backup)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 bg-primary-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-file-archive text-primary-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $backup['name'] }}</p>
                                        @if($index === 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700 mt-0.5">
                                            <i class="fas fa-star mr-1 text-xs"></i> Terbaru
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \App\Http\Controllers\Admin\BackupController::formatBytes($backup['size']) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div>{{ $backup['created_at']->format('d M Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $backup['created_at']->format('H:i:s') }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.backup.download', $backup['name']) }}"
                                       class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-colors">
                                        <i class="fas fa-download mr-1.5"></i> Unduh
                                    </a>
                                    <form method="POST"
                                          action="{{ route('admin.backup.destroy', $backup['name']) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus backup ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition-colors">
                                            <i class="fas fa-trash mr-1.5"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmBackup(form) {
    const btn = document.getElementById('btn-backup');
    if (!confirm('Apakah Anda yakin ingin membuat backup database sekarang?\n\nProses ini mungkin memakan beberapa detik.')) {
        return false;
    }
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Sedang Membuat Backup...';
    return true;
}
</script>
@endpush
