<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use ZipArchive;

class BackupController extends Controller
{
    /**
     * Display list of backups.
     */
    public function index()
    {
        $backups = [];

        if (Storage::disk('local')->exists('backups')) {
            $files = Storage::disk('local')->files('backups');
            foreach ($files as $file) {
                $backups[] = [
                    'name'      => basename($file),
                    'path'      => $file,
                    'size'      => Storage::disk('local')->size($file),
                    'created_at'=> Carbon::createFromTimestamp(Storage::disk('local')->lastModified($file)),
                ];
            }
            // Sort newest first
            usort($backups, fn($a, $b) => $b['created_at']->timestamp - $a['created_at']->timestamp);
        }

        return view('admin.backup.index', compact('backups'));
    }

    /**
     * Create a new database backup.
     */
    public function create(Request $request)
    {
        try {
            $dbHost     = config('database.connections.mysql.host', '127.0.0.1');
            $dbPort     = config('database.connections.mysql.port', '3306');
            $dbName     = config('database.connections.mysql.database');
            $dbUser     = config('database.connections.mysql.username');
            $dbPassword = config('database.connections.mysql.password');

            $timestamp  = Carbon::now()->format('Y-m-d_H-i-s');
            $sqlFile    = storage_path("app/backups/{$dbName}_{$timestamp}.sql");
            $zipFile    = storage_path("app/backups/{$dbName}_{$timestamp}.zip");

            // Ensure backup directory exists
            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }

            // Build mysqldump command
            $passwordPart = $dbPassword ? "-p" . escapeshellarg($dbPassword) : '';
            $command = sprintf(
                'mysqldump --host=%s --port=%s --user=%s %s %s > %s 2>&1',
                escapeshellarg($dbHost),
                escapeshellarg($dbPort),
                escapeshellarg($dbUser),
                $passwordPart,
                escapeshellarg($dbName),
                escapeshellarg($sqlFile)
            );

            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                return back()->with('error', 'Gagal membuat backup: ' . implode("\n", $output));
            }

            if (!file_exists($sqlFile) || filesize($sqlFile) === 0) {
                return back()->with('error', 'File backup kosong atau tidak terbuat. Pastikan mysqldump tersedia.');
            }

            // Compress to ZIP
            $zip = new ZipArchive();
            if ($zip->open($zipFile, ZipArchive::CREATE) === true) {
                $zip->addFile($sqlFile, basename($sqlFile));
                $zip->close();
                unlink($sqlFile); // Remove raw SQL file
            }

            return back()->with('success', "Backup berhasil dibuat: " . basename($zipFile));

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Download a backup file.
     */
    public function download(Request $request, string $filename)
    {
        $path = storage_path("app/backups/{$filename}");

        if (!file_exists($path)) {
            return back()->with('error', 'File backup tidak ditemukan.');
        }

        return response()->download($path);
    }

    /**
     * Delete a backup file.
     */
    public function destroy(Request $request, string $filename)
    {
        $path = "backups/{$filename}";

        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
            return back()->with('success', 'Backup berhasil dihapus.');
        }

        return back()->with('error', 'File backup tidak ditemukan.');
    }

    /**
     * Format bytes to human readable.
     */
    public static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow   = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow   = min($pow, count($units) - 1);
        return round($bytes / (1024 ** $pow), $precision) . ' ' . $units[$pow];
    }
}
