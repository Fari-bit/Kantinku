<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranPenjual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PendaftaranPenjualController extends Controller
{
    public function create()
    {
        return view('pendaftaran-penjual');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'          => 'required|string|max:255',
            'email'                 => 'required|email|unique:pendaftaran_penjual,email|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
            'phone'                 => 'required|string|max:20',
            'nama_warung'           => 'required|string|max:255',
            'deskripsi_warung'      => 'required|string|min:20',
            'jenis_makanan'         => 'required|string|max:100',
            'terima_transfer'       => 'nullable|boolean',
            'nama_bank'             => 'required_if:terima_transfer,1|nullable|string|max:100',
            'nomor_rekening'        => 'required_if:terima_transfer,1|nullable|string|max:50',
            'nama_pemilik_rekening' => 'required_if:terima_transfer,1|nullable|string|max:255',
            'foto_ktp'              => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_warung'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'nama_bank.required_if'             => 'Nama bank wajib diisi jika menerima transfer.',
            'nomor_rekening.required_if'         => 'Nomor rekening wajib diisi jika menerima transfer.',
            'nama_pemilik_rekening.required_if'  => 'Nama pemilik rekening wajib diisi jika menerima transfer.',
        ]);

        $data = $request->only([
            'nama_lengkap', 'email', 'phone',
            'nama_warung', 'deskripsi_warung', 'jenis_makanan',
            'nama_bank', 'nomor_rekening', 'nama_pemilik_rekening',
        ]);

        // FIX #4: simpan password (terenkripsi)
        $data['password']        = Hash::make($request->password);
        $data['terima_transfer'] = $request->boolean('terima_transfer');

        if ($request->hasFile('foto_ktp')) {
            $data['foto_ktp'] = $request->file('foto_ktp')->store('pendaftaran', 'public');
        }
        if ($request->hasFile('foto_warung')) {
            $data['foto_warung'] = $request->file('foto_warung')->store('pendaftaran', 'public');
        }

        PendaftaranPenjual::create($data);

        return redirect()->route('welcome')
            ->with('success', 'Pendaftaran berhasil dikirim! Admin akan menghubungi kamu melalui email setelah diverifikasi.');
    }
}
