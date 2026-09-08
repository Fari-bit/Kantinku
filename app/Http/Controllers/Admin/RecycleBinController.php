<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecycleBinController extends Controller
{
    /**
     * Menampilkan daftar akun (penjual & siswa) yang sudah dihapus
     * (soft delete). Bisa difilter per role lewat query ?role=penjual|siswa.
     */
    public function index(Request $request)
    {
        $role = $request->query('role');

        $query = User::onlyTrashed()
            ->whereIn('role', ['penjual', 'siswa'])
            ->orderBy('deleted_at', 'desc');

        if (in_array($role, ['penjual', 'siswa'])) {
            $query->where('role', $role);
        }

        $users = $query->paginate(10)->withQueryString();

        $totalPenjual = User::onlyTrashed()->where('role', 'penjual')->count();
        $totalSiswa   = User::onlyTrashed()->where('role', 'siswa')->count();

        return view('admin.recycle-bin.index', compact('users', 'role', 'totalPenjual', 'totalSiswa'));
    }

    /**
     * Memulihkan akun dari recycle bin.
     */
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return back()->with('success', 'Akun "' . $user->name . '" berhasil dipulihkan!');
    }

    /**
     * Menghapus akun secara permanen (tidak bisa dipulihkan lagi).
     */
    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->with('penjualProfile')->findOrFail($id);

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        if ($user->penjualProfile?->foto_warung) {
            Storage::disk('public')->delete($user->penjualProfile->foto_warung);
        }

        $nama = $user->name;
        $user->forceDelete();

        return back()->with('success', 'Akun "' . $nama . '" dihapus permanen.');
    }

    /**
     * Mengosongkan seluruh recycle bin akun (penjual + siswa) sekaligus.
     */
    public function empty()
    {
        $users = User::onlyTrashed()->whereIn('role', ['penjual', 'siswa'])->with('penjualProfile')->get();

        foreach ($users as $user) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            if ($user->penjualProfile?->foto_warung) {
                Storage::disk('public')->delete($user->penjualProfile->foto_warung);
            }
            $user->forceDelete();
        }

        return back()->with('success', 'Recycle Bin berhasil dikosongkan.');
    }
}
