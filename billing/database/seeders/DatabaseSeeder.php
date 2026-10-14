<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MahasiswaRef;
use App\Models\TagihanPembayaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin Billing
        User::create([
            'name' => 'Staf Keuangan Kampus',
            'email' => 'admin@billing.kampus.id',
            'role' => 'admin_keuangan',
            'password' => Hash::make('password123'),
        ]);

        // 2. Data Referensi Mahasiswa
        $mahasiswaRefs = [
            ['nim' => '210101001', 'nama' => 'Dzulfikri Alifajri', 'prodi' => 'Teknik Informatika', 'angkatan' => '2021'],
            ['nim' => '210101002', 'nama' => 'Budi Santoso', 'prodi' => 'Teknik Informatika', 'angkatan' => '2021'],
            ['nim' => '210101003', 'nama' => 'Siti Rahmawati', 'prodi' => 'Sistem Informasi', 'angkatan' => '2021'],
            ['nim' => '200101004', 'nama' => 'Ahmad Fauzan', 'prodi' => 'Teknik Informatika', 'angkatan' => '2020'],
            ['nim' => '200101005', 'nama' => 'Rina Kusuma', 'prodi' => 'Teknik Informatika', 'angkatan' => '2020'],
        ];

        foreach ($mahasiswaRefs as $ref) {
            MahasiswaRef::create($ref);
        }

        // 3. Tagihan Pembayaran
        // 3.1 Dzulfikri (UKT Semester 7 UNPAID)
        TagihanPembayaran::create([
            'nomor_invoice' => 'INV-UKT-2026-7-001',
            'nim' => '210101001',
            'jenis_tagihan' => 'UKT',
            'deskripsi' => 'Uang Kuliah Tunggal (UKT) Semester 7 T.A 2026/2027',
            'semester' => '7',
            'jumlah_tagihan' => 4500000,
            'nomor_va' => '8808210101001001',
            'status_pembayaran' => 'UNPAID',
            'tanggal_jatuh_tempo' => Carbon::now()->addDays(14),
        ]);

        // 3.2 Budi Santoso (UKT Semester 7 PAID)
        TagihanPembayaran::create([
            'nomor_invoice' => 'INV-UKT-2026-7-002',
            'nim' => '210101002',
            'jenis_tagihan' => 'UKT',
            'deskripsi' => 'Uang Kuliah Tunggal (UKT) Semester 7 T.A 2026/2027',
            'semester' => '7',
            'jumlah_tagihan' => 4500000,
            'nomor_va' => '8808210101002001',
            'status_pembayaran' => 'PAID',
            'tanggal_jatuh_tempo' => Carbon::now()->addDays(14),
            'tanggal_bayar' => Carbon::now()->subDays(5),
            'channel_bayar' => 'M-BANKING MANDIRI',
        ]);

        // 3.3 Siti Rahmawati (UKT Semester 7 PAID)
        TagihanPembayaran::create([
            'nomor_invoice' => 'INV-UKT-2026-7-003',
            'nim' => '210101003',
            'jenis_tagihan' => 'UKT',
            'deskripsi' => 'Uang Kuliah Tunggal (UKT) Semester 7 T.A 2026/2027',
            'semester' => '7',
            'jumlah_tagihan' => 4200000,
            'nomor_va' => '8808210101003001',
            'status_pembayaran' => 'PAID',
            'tanggal_jatuh_tempo' => Carbon::now()->addDays(14),
            'tanggal_bayar' => Carbon::now()->subDays(3),
            'channel_bayar' => 'ATM BNI',
        ]);

        // 3.4 Ahmad Fauzan (UKT Smt 8 LUNAS, tapi punya DENDA keterlambatan Rp 250.000 UNPAID)
        TagihanPembayaran::create([
            'nomor_invoice' => 'INV-UKT-2026-8-004',
            'nim' => '200101004',
            'jenis_tagihan' => 'UKT',
            'deskripsi' => 'Uang Kuliah Tunggal (UKT) Semester 8 T.A 2026/2027',
            'semester' => '8',
            'jumlah_tagihan' => 4500000,
            'nomor_va' => '8808200101004001',
            'status_pembayaran' => 'PAID',
            'tanggal_jatuh_tempo' => Carbon::now()->subDays(30),
            'tanggal_bayar' => Carbon::now()->subDays(25),
            'channel_bayar' => 'M-BANKING BCA',
        ]);
        TagihanPembayaran::create([
            'nomor_invoice' => 'INV-DND-2026-004',
            'nim' => '200101004',
            'jenis_tagihan' => 'DENDA',
            'deskripsi' => 'Denda Keterlambatan Pengembalian Alat Laboratorium Jaringan',
            'semester' => '7',
            'jumlah_tagihan' => 250000,
            'nomor_va' => '8808200101004099',
            'status_pembayaran' => 'UNPAID',
            'tanggal_jatuh_tempo' => Carbon::now()->addDays(7),
        ]);

        // 3.5 Rina Kusuma (UKT Smt 8 LUNAS, tidak ada denda)
        TagihanPembayaran::create([
            'nomor_invoice' => 'INV-UKT-2026-8-005',
            'nim' => '200101005',
            'jenis_tagihan' => 'UKT',
            'deskripsi' => 'Uang Kuliah Tunggal (UKT) Semester 8 T.A 2026/2027',
            'semester' => '8',
            'jumlah_tagihan' => 4500000,
            'nomor_va' => '8808200101005001',
            'status_pembayaran' => 'PAID',
            'tanggal_jatuh_tempo' => Carbon::now()->subDays(30),
            'tanggal_bayar' => Carbon::now()->subDays(28),
            'channel_bayar' => 'M-BANKING BRI',
        ]);
    }
}
