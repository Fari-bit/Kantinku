<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PenjualProfile;
use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ────────────────────────────────────────────────────────────
        User::create([
            'name'     => 'Admin KantinKu',
            'email'    => 'admin@kantinku.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'status'   => 'active',
        ]);

        // ── Penjual 1 ─────────────────────────────────────────────────────────
        $penjual1 = User::create([
            'name'     => 'Bu Sari',
            'email'    => 'busari@kantinku.com',
            'password' => Hash::make('password'),
            'role'     => 'penjual',
            'phone'    => '081234567890',
            'status'   => 'active',
        ]);
        PenjualProfile::create([
            'user_id'     => $penjual1->id,
            'nama_warung' => 'Warung Bu Sari',
            'deskripsi'   => 'Spesialis nasi dan lauk pauk rumahan dengan cita rasa autentik.',
            'nomor_stand' => 'A1',
            'is_open'     => true,
            'jam_buka'    => '07:00',
            'jam_tutup'   => '14:00',
        ]);
        $menus1 = [
            ['nama' => 'Nasi Goreng Spesial',  'harga' => 12000, 'kategori' => 'makanan', 'stok' => 50, 'terjual' => 120],
            ['nama' => 'Nasi Ayam Goreng',      'harga' => 13000, 'kategori' => 'makanan', 'stok' => 40, 'terjual' => 95],
            ['nama' => 'Nasi Rendang',           'harga' => 15000, 'kategori' => 'makanan', 'stok' => 30, 'terjual' => 80],
            ['nama' => 'Es Teh Manis',           'harga' => 3000,  'kategori' => 'minuman', 'stok' => 100,'terjual' => 200],
            ['nama' => 'Es Jeruk',               'harga' => 4000,  'kategori' => 'minuman', 'stok' => 80, 'terjual' => 150],
        ];
        foreach ($menus1 as $m) {
            Menu::create(array_merge($m, ['penjual_id' => $penjual1->id, 'tersedia' => true]));
        }

        // ── Penjual 2 ─────────────────────────────────────────────────────────
        $penjual2 = User::create([
            'name'     => 'Pak Budi',
            'email'    => 'pakbudi@kantinku.com',
            'password' => Hash::make('password'),
            'role'     => 'penjual',
            'phone'    => '082345678901',
            'status'   => 'active',
        ]);
        PenjualProfile::create([
            'user_id'     => $penjual2->id,
            'nama_warung' => 'Snack Corner Pak Budi',
            'deskripsi'   => 'Aneka gorengan, jajanan, dan minuman segar untuk teman istirahat.',
            'nomor_stand' => 'B2',
            'is_open'     => true,
            'jam_buka'    => '06:30',
            'jam_tutup'   => '14:30',
        ]);
        $menus2 = [
            ['nama' => 'Bakwan Sayur',    'harga' => 2000,  'kategori' => 'snack',   'stok' => 100, 'terjual' => 300],
            ['nama' => 'Tahu Goreng',     'harga' => 1500,  'kategori' => 'snack',   'stok' => 100, 'terjual' => 250],
            ['nama' => 'Pisang Goreng',   'harga' => 2500,  'kategori' => 'snack',   'stok' => 80,  'terjual' => 180],
            ['nama' => 'Mie Goreng',      'harga' => 8000,  'kategori' => 'makanan', 'stok' => 50,  'terjual' => 120],
            ['nama' => 'Jus Alpukat',     'harga' => 8000,  'kategori' => 'minuman', 'stok' => 30,  'terjual' => 90],
            ['nama' => 'Boba Taro',       'harga' => 10000, 'kategori' => 'minuman', 'stok' => 40,  'terjual' => 75],
        ];
        foreach ($menus2 as $m) {
            Menu::create(array_merge($m, ['penjual_id' => $penjual2->id, 'tersedia' => true]));
        }

        // ── Siswa ─────────────────────────────────────────────────────────────
        $siswaData = [
            ['name' => 'Budi Santoso',   'email' => 'budi@siswa.com',   'kelas' => 'XII RPL 1', 'nis' => '2024001'],
            ['name' => 'Siti Rahayu',    'email' => 'siti@siswa.com',   'kelas' => 'XII TKJ 2', 'nis' => '2024002'],
            ['name' => 'Ahmad Fauzi',    'email' => 'ahmad@siswa.com',  'kelas' => 'XI AKL 1',  'nis' => '2024003'],
            ['name' => 'Dewi Lestari',   'email' => 'dewi@siswa.com',   'kelas' => 'X MM 1',    'nis' => '2024004'],
        ];
        foreach ($siswaData as $s) {
            User::create(array_merge($s, [
                'password' => Hash::make('password'),
                'role'     => 'siswa',
                'status'   => 'active',
            ]));
        }

        $this->command->info('✅ Seeding selesai!');
        $this->command->info('');
        $this->command->info('Akun tersedia:');
        $this->command->info('  Admin   → admin@kantinku.com   / password');
        $this->command->info('  Penjual → busari@kantinku.com  / password');
        $this->command->info('  Penjual → pakbudi@kantinku.com / password');
        $this->command->info('  Siswa   → budi@siswa.com       / password');
    }
}
