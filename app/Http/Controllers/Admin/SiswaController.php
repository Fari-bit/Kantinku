<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = User::where('role', 'siswa')
            ->withCount('pesananSebagaiSiswa')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.siswa.index', compact('siswa'));
    }

    public function show(User $siswa)
    {
        $siswa->load(['pesananSebagaiSiswa.details', 'pesananSebagaiSiswa.penjual']);
        return view('admin.siswa.show', compact('siswa'));
    }

    public function toggleStatus(User $siswa)
    {
        $siswa->update([
            'status' => $siswa->status === 'active' ? 'inactive' : 'active'
        ]);
        return back()->with('success', 'Status siswa berhasil diubah.');
    }

    public function destroy(User $siswa)
    {
        $siswa->delete();
        return redirect()->route('admin.siswa.index')
            ->with('success', 'Akun siswa berhasil dihapus.');
    }
}
