<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user()->load('penjualProfile');
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'phone'       => ['nullable', 'string', 'max:20'],
            'kelas'       => ['nullable', 'string', 'max:50'],
            'nis'         => ['nullable', 'string', 'max:20'],
            'avatar'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'foto_warung' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = $request->only(['name', 'phone', 'kelas', 'nis']);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        if ($user->isPenjual() && $request->filled('nama_warung')) {
            $profileData = $request->only([
                'nama_warung', 'deskripsi', 'nomor_stand', 'jam_buka', 'jam_tutup',
                'nama_bank', 'nomor_rekening', 'nama_pemilik_rekening',
            ]);
            $profileData['terima_transfer'] = $request->boolean('terima_transfer');

            if ($request->hasFile('foto_warung')) {
                if ($user->penjualProfile?->foto_warung) {
                    Storage::disk('public')->delete($user->penjualProfile->foto_warung);
                }
                $profileData['foto_warung'] = $request->file('foto_warung')->store('warung', 'public');
            }

            $user->penjualProfile()->updateOrCreate(
                ['user_id' => $user->id],
                $profileData
            );
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        auth()->user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password berhasil diperbarui!');
    }

    public function destroy(Request $request)
    {
        $request->validate(['password' => ['required']]);

        if (!Hash::check($request->password, auth()->user()->password)) {
            return back()->withErrors(['password' => 'Password tidak sesuai.']);
        }

        $user = auth()->user();
        auth()->logout();

        // Soft delete: akun dipindahkan ke Recycle Bin (bisa dipulihkan
        // admin) — avatar tidak langsung dihapus.
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome')->with('success', 'Akun berhasil dihapus.');
    }
}
