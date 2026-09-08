<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BackupDatabaseMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class BackupController extends Controller
{
    protected string $disk = 'local';
    protected string $folder = 'backups';

    /**
     * Menampilkan daftar file backup.
     */
    public function index()
    {
        $files = collect(
            Storage::disk($this->disk)->files($this->folder)
        )
            ->filter(fn ($path) => str_ends_with($path, '.sql'))
            ->map(function ($path) {
                return [
                    'name' => basename($path),
                    'size' => $this->formatSize(
                        Storage::disk($this->disk)->size($path)
                    ),
                    'date' => Storage::disk($this->disk)->lastModified($path),
                ];
            })
            ->sortByDesc('date')
            ->values();

        return view('admin.backup.index', [
            'backups' => $files
        ]);
    }

    /**
     * Backup database secara manual.
     *
     * Email tujuan dikirim melalui form.
     */
    public function create(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        return $this->performBackup($request->email);
    }

    /**
     * Backup database otomatis.
     *
     * Email tujuan diambil dari AUTO_BACKUP_EMAIL
     * pada file .env.
     */
    public function createAutomaticBackup()
    {
        $email = env('AUTO_BACKUP_EMAIL');

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'AUTO_BACKUP_EMAIL belum dikonfigurasi dengan benar.'
            ];
        }

        return $this->performBackup($email, true);
    }

    /**
     * Proses utama pembuatan dan pengiriman backup.
     */
    protected function performBackup(string $email, bool $automatic = false)
    {
        $filename = 'backup_' . now()->format('Y-m-d_His') . '.sql';

        $path = storage_path(
            "app/{$this->folder}/{$filename}"
        );

        // Membuat folder backup jika belum ada.
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $db = config('database.connections.mysql');

        $mysqldump = env('MYSQLDUMP_PATH', 'mysqldump');

        $command = [
            $mysqldump,
            '-h', $db['host'],
            '-P', $db['port'],
            '-u', $db['username'],
            '--password=' . $db['password'],
            $db['database'],
        ];

        $process = new Process($command);

        $process->setTimeout(300);

        // Environment Windows/Laragon.
        $process->setEnv([
            'SystemRoot' => getenv('SystemRoot') ?: 'C:\\Windows',
            'WINDIR'     => getenv('WINDIR') ?: 'C:\\Windows',
            'TEMP'       => getenv('TEMP') ?: sys_get_temp_dir(),
            'TMP'        => getenv('TMP') ?: sys_get_temp_dir(),
        ]);

        // Menjalankan mysqldump.
        $process->run(function ($type, $buffer) use ($path) {
            file_put_contents(
                $path,
                $buffer,
                FILE_APPEND
            );
        });

        // Mengecek apakah backup berhasil.
        if (
            !$process->isSuccessful() ||
            !file_exists($path) ||
            filesize($path) === 0
        ) {
            $errorOutput = trim(
                $process->getErrorOutput()
            );

            if (file_exists($path)) {
                unlink($path);
            }

            $message =
                'Backup gagal dibuat. ' .
                ($errorOutput ?: 'mysqldump tidak ditemukan. Cek MYSQLDUMP_PATH di .env.');

            if ($automatic) {
                return [
                    'success' => false,
                    'message' => $message
                ];
            }

            return back()->with('error', $message);
        }

        // Mengirim backup melalui email.
        try {
            Mail::to($email)->send(
                new BackupDatabaseMail($path, $filename)
            );
        } catch (\Throwable $e) {

            if ($automatic) {
                return [
                    'success' => false,
                    'message' => 'Backup berhasil dibuat tetapi gagal dikirim melalui email: ' . $e->getMessage()
                ];
            }

            return back()->with(
                'error',
                'Backup tersimpan tetapi gagal dikirim email: ' . $e->getMessage()
            );
        }

        // Response untuk backup otomatis.
        if ($automatic) {
            return [
                'success' => true,
                'message' => "Backup otomatis berhasil dibuat dan dikirim ke {$email}.",
                'filename' => $filename
            ];
        }

        // Response untuk backup manual.
        return back()->with(
            'success',
            "Backup berhasil dibuat dan dikirim ke {$email}."
        );
    }

    /**
     * Download file backup.
     */
    public function download(string $filename)
    {
        $path = "{$this->folder}/{$filename}";

        if (
            !$this->isValidFilename($filename) ||
            !Storage::disk($this->disk)->exists($path)
        ) {
            abort(404);
        }

        return Storage::disk($this->disk)->download($path);
    }

    /**
     * Hapus file backup.
     */
    public function delete(string $filename)
    {
        $path = "{$this->folder}/{$filename}";

        if (
            !$this->isValidFilename($filename) ||
            !Storage::disk($this->disk)->exists($path)
        ) {
            return back()->with(
                'error',
                'File backup tidak ditemukan.'
            );
        }

        Storage::disk($this->disk)->delete($path);

        return back()->with(
            'success',
            'Backup berhasil dihapus.'
        );
    }

    /**
     * Validasi nama file backup.
     */
    protected function isValidFilename(string $filename): bool
    {
        return (bool) preg_match(
            '/^backup_[\d\-_]+\.sql$/',
            $filename
        );
    }

    /**
     * Format ukuran file.
     */
    protected function formatSize(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format(
                $bytes / 1048576,
                2
            ) . ' MB';
        }

        if ($bytes >= 1024) {
            return number_format(
                $bytes / 1024,
                2
            ) . ' KB';
        }

        return $bytes . ' B';
    }

    /**
     * Mengubah pengaturan backup otomatis.
     */
    public function updateAutoBackupSettings(Request $request)
    {
        $request->validate([
            'enabled' => ['required', 'in:true,false'],
            'interval' => ['required', 'numeric', 'min:0.01', 'max:24'],
        ]);

        $path = base_path('.env');

        if (file_exists($path)) {
            $envContent = file_get_contents($path);

            // AUTO_BACKUP_ENABLED
            if (str_contains($envContent, 'AUTO_BACKUP_ENABLED=')) {
                $envContent = preg_replace(
                    '/^AUTO_BACKUP_ENABLED=.*/m',
                    'AUTO_BACKUP_ENABLED=' . $request->enabled,
                    $envContent
                );
            } else {
                $envContent .= "\nAUTO_BACKUP_ENABLED=" . $request->enabled;
            }

            // AUTO_BACKUP_INTERVAL
            if (str_contains($envContent, 'AUTO_BACKUP_INTERVAL=')) {
                $envContent = preg_replace(
                    '/^AUTO_BACKUP_INTERVAL=.*/m',
                    'AUTO_BACKUP_INTERVAL=' . $request->interval,
                    $envContent
                );
            } else {
                $envContent .= "\nAUTO_BACKUP_INTERVAL=" . $request->interval;
            }

            file_put_contents($path, $envContent);
        }

        return redirect()
            ->back()
            ->with(
                'success',
                'Pengaturan otomatisasi backup berhasil diperbarui!'
            );
    }

    /**
     * Mengaktifkan / menonaktifkan backup otomatis.
     */
    public function toggleAutoBackup()
    {
        $currentStatus = filter_var(
            env('AUTO_BACKUP_ENABLED', false),
            FILTER_VALIDATE_BOOLEAN
        );

        $newStatus = $currentStatus ? 'false' : 'true';

        $path = base_path('.env');

        if (file_exists($path)) {
            $envContent = file_get_contents($path);

            if (str_contains($envContent, 'AUTO_BACKUP_ENABLED=')) {
                $envContent = preg_replace(
                    '/^AUTO_BACKUP_ENABLED=.*/m',
                    'AUTO_BACKUP_ENABLED=' . $newStatus,
                    $envContent
                );
            } else {
                $envContent .= "\nAUTO_BACKUP_ENABLED=" . $newStatus;
            }

            file_put_contents($path, $envContent);
        }

        // Bersihkan cache konfigurasi.
        \Illuminate\Support\Facades\Artisan::call(
            'config:clear'
        );

        $message = $newStatus === 'true'
            ? 'Otomatisasi backup BERHASIL DIAKTIFKAN!'
            : 'Otomatisasi backup BERHASIL DIMATIKAN!';

        return redirect()
            ->back()
            ->with('success', $message);
    }
}