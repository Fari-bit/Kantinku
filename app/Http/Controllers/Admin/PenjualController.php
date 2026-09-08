<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PenjualProfile;
use App\Models\PendaftaranPenjual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PenjualController extends Controller
{
    public function index()
    {
        $penjual = User::where('role', 'penjual')
            ->with('penjualProfile')
            ->withCount('menus')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.penjual.index', compact('penjual'));
    }

    public function create()
    {
        return view('admin.penjual.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|min:8|confirmed',
            'phone'                 => 'nullable|string|max:20',
            'nama_warung'           => 'required|string|max:255',
            'deskripsi'             => 'nullable|string',
            'nomor_stand'           => 'nullable|string|max:50',
            'foto_warung'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'terima_transfer'       => 'nullable|boolean',
            'nama_bank'             => 'required_if:terima_transfer,1|nullable|string|max:100',
            'nomor_rekening'        => 'required_if:terima_transfer,1|nullable|string|max:50',
            'nama_pemilik_rekening' => 'required_if:terima_transfer,1|nullable|string|max:255',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'penjual',
            'phone'    => $request->phone,
            'status'   => 'active',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_warung')) {
            $fotoPath = $request->file('foto_warung')->store('warung', 'public');
        }

        PenjualProfile::create([
            'user_id'               => $user->id,
            'nama_warung'           => $request->nama_warung,
            'deskripsi'             => $request->deskripsi,
            'nomor_stand'           => $request->nomor_stand,
            'foto_warung'           => $fotoPath,
            'jam_buka'              => $request->jam_buka ?? '07:00',
            'jam_tutup'             => $request->jam_tutup ?? '14:00',
            'terima_transfer'       => $request->boolean('terima_transfer'),
            'nama_bank'             => $request->nama_bank,
            'nomor_rekening'        => $request->nomor_rekening,
            'nama_pemilik_rekening' => $request->nama_pemilik_rekening,
        ]);

        return redirect()->route('admin.penjual.index')
            ->with('success', 'Penjual berhasil ditambahkan!');
    }

    public function edit(User $penjual)
    {
        $penjual->load('penjualProfile');
        return view('admin.penjual.edit', compact('penjual'));
    }

    public function update(Request $request, User $penjual)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $penjual->id,
            'phone'       => 'nullable|string|max:20',
            'nama_warung' => 'required|string|max:255',
            'status'      => 'required|in:active,inactive',
        ]);

        $penjual->update([
            'name'   => $request->name,
            'email'  => $request->email,
            'phone'  => $request->phone,
            'status' => $request->status,
        ]);

        if ($request->filled('password')) {
            $penjual->update(['password' => Hash::make($request->password)]);
        }

        $profileData = [
            'nama_warung'           => $request->nama_warung,
            'deskripsi'             => $request->deskripsi,
            'nomor_stand'           => $request->nomor_stand,
            'jam_buka'              => $request->jam_buka,
            'jam_tutup'             => $request->jam_tutup,
            'terima_transfer'       => $request->boolean('terima_transfer'),
            'nama_bank'             => $request->nama_bank,
            'nomor_rekening'        => $request->nomor_rekening,
            'nama_pemilik_rekening' => $request->nama_pemilik_rekening,
        ];

        if ($request->hasFile('foto_warung')) {
            if ($penjual->penjualProfile?->foto_warung) {
                Storage::disk('public')->delete($penjual->penjualProfile->foto_warung);
            }
            $profileData['foto_warung'] = $request->file('foto_warung')->store('warung', 'public');
        }

        $penjual->penjualProfile()->updateOrCreate(
            ['user_id' => $penjual->id],
            $profileData
        );

        return redirect()->route('admin.penjual.index')
            ->with('success', 'Data penjual berhasil diperbarui!');
    }

    public function destroy(User $penjual)
    {
        // Soft delete: akun penjual dipindahkan ke Recycle Bin dulu,
        // foto warung TIDAK langsung dihapus supaya bisa dipulihkan.
        $penjual->delete();
        return redirect()->route('admin.penjual.index')
            ->with('success', 'Penjual dipindahkan ke Recycle Bin!');
    }

    /* ── Pendaftaran ── */

    public function pendaftaran()
    {
        $pendaftaran = PendaftaranPenjual::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.penjual.pendaftaran', compact('pendaftaran'));
    }

    public function showPendaftaran(PendaftaranPenjual $pendaftaran)
    {
        return view('admin.penjual.show-pendaftaran', compact('pendaftaran'));
    }

    public function approvePendaftaran(Request $request, PendaftaranPenjual $pendaftaran)
    {
        $request->validate(['catatan_admin' => 'nullable|string']);

        // Gunakan password yang sudah ada dari form pendaftaran
        // Jika password di DB sudah di-hash (dari controller baru), pakai langsung
        // Jika belum (data lama), generate baru
        $passwordHash = $pendaftaran->password ?? Hash::make(Str::random(10));

        $user = User::create([
            'name'     => $pendaftaran->nama_lengkap,
            'email'    => $pendaftaran->email,
            'password' => $passwordHash,
            'role'     => 'penjual',
            'phone'    => $pendaftaran->phone,
            'status'   => 'active',
        ]);

        PenjualProfile::create([
            'user_id'               => $user->id,
            'nama_warung'           => $pendaftaran->nama_warung,
            'deskripsi'             => $pendaftaran->deskripsi_warung,
            'foto_warung'           => $pendaftaran->foto_warung,
            'terima_transfer'       => $pendaftaran->terima_transfer,
            'nama_bank'             => $pendaftaran->nama_bank,
            'nomor_rekening'        => $pendaftaran->nomor_rekening,
            'nama_pemilik_rekening' => $pendaftaran->nama_pemilik_rekening,
        ]);

        $pendaftaran->update([
            'status'        => 'approved',
            'catatan_admin' => $request->catatan_admin,
            'reviewed_by'   => auth()->id(),
            'reviewed_at'   => now(),
        ]);

        return redirect()->route('admin.penjual.pendaftaran')
            ->with('success', 'Pendaftaran disetujui! Akun penjual berhasil dibuat. Password sesuai yang didaftarkan.');
    }

    public function rejectPendaftaran(Request $request, PendaftaranPenjual $pendaftaran)
    {
        $request->validate(['catatan_admin' => 'required|string']);

        $pendaftaran->update([
            'status'        => 'rejected',
            'catatan_admin' => $request->catatan_admin,
            'reviewed_by'   => auth()->id(),
            'reviewed_at'   => now(),
        ]);

        return redirect()->route('admin.penjual.pendaftaran')
            ->with('success', 'Pendaftaran berhasil ditolak.');
    }
}
