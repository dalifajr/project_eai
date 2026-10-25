<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin Akademik
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator SIMAK',
                'email' => 'admin@simak.kampus.id',
                'role' => 'admin',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Data Mahasiswa & Akun Mahasiswa
        $dataMahasiswa = [
            [
                'nim' => '210101001',
                'nama' => 'Dzulfikri Alifajri',
                'program_studi' => 'Teknik Informatika',
                'fakultas' => 'Ilmu Komputer',
                'angkatan' => '2021',
                'status_akademik' => 'aktif',
                'status_skripsi' => 'belum',
                'bebas_lab' => false,
                'bebas_perpustakaan' => false,
                'ips_lalu' => 3.75,
                'batas_sks' => 24,
                'semester_sekarang' => '7',
            ],
            [
                'nim' => '210101002',
                'nama' => 'Budi Santoso',
                'program_studi' => 'Teknik Informatika',
                'fakultas' => 'Ilmu Komputer',
                'angkatan' => '2021',
                'status_akademik' => 'aktif',
                'status_skripsi' => 'belum',
                'bebas_lab' => false,
                'bebas_perpustakaan' => false,
                'ips_lalu' => 3.40,
                'batas_sks' => 22,
                'semester_sekarang' => '7',
            ],
            [
                'nim' => '210101003',
                'nama' => 'Siti Rahmawati',
                'program_studi' => 'Sistem Informasi',
                'fakultas' => 'Ilmu Komputer',
                'angkatan' => '2021',
                'status_akademik' => 'aktif',
                'status_skripsi' => 'proposal',
                'bebas_lab' => true,
                'bebas_perpustakaan' => true,
                'ips_lalu' => 3.85,
                'batas_sks' => 24,
                'semester_sekarang' => '7',
            ],
            [
                'nim' => '200101004',
                'nama' => 'Ahmad Fauzan',
                'program_studi' => 'Teknik Informatika',
                'fakultas' => 'Ilmu Komputer',
                'angkatan' => '2020',
                'status_akademik' => 'aktif',
                'status_skripsi' => 'lulus',
                'bebas_lab' => true,
                'bebas_perpustakaan' => true,
                'ips_lalu' => 3.65,
                'batas_sks' => 24,
                'semester_sekarang' => '8',
            ],
            [
                'nim' => '200101005',
                'nama' => 'Rina Kusuma',
                'program_studi' => 'Teknik Informatika',
                'fakultas' => 'Ilmu Komputer',
                'angkatan' => '2020',
                'status_akademik' => 'aktif',
                'status_skripsi' => 'lulus',
                'bebas_lab' => true,
                'bebas_perpustakaan' => true,
                'ips_lalu' => 3.90,
                'batas_sks' => 24,
                'semester_sekarang' => '8',
            ],
        ];

        foreach ($dataMahasiswa as $mhs) {
            Mahasiswa::updateOrCreate(
                ['nim' => $mhs['nim']],
                $mhs
            );

            User::updateOrCreate(
                ['username' => $mhs['nim']],
                [
                    'name' => $mhs['nama'],
                    'email' => $mhs['nim'] . '@student.kampus.id',
                    'role' => 'mahasiswa',
                    'nim' => $mhs['nim'],
                    'password' => Hash::make('password123'),
                ]
            );
        }

        // 3. Data Mata Kuliah & Jadwal Kuliah
        $mataKuliah = [
            [
                'kode_mk' => 'TIF701',
                'nama_mk' => 'Arsitektur Enterprise',
                'sks' => 3,
                'semester' => '7',
                'hari' => 'Senin',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:30:00',
                'ruangan' => 'Lab Komputer 1',
                'kuota' => 35,
            ],
            [
                'kode_mk' => 'TIF702',
                'nama_mk' => 'Integrasi Aplikasi Enterprise (EAI)',
                'sks' => 3,
                'semester' => '7',
                'hari' => 'Senin',
                'jam_mulai' => '08:00:00', // Bentrok dengan TIF701 untuk validasi
                'jam_selesai' => '10:30:00',
                'ruangan' => 'Lab Komputer 2',
                'kuota' => 35,
            ],
            [
                'kode_mk' => 'TIF703',
                'nama_mk' => 'Keamanan Sistem Informasi & Jaringan',
                'sks' => 3,
                'semester' => '7',
                'hari' => 'Selasa',
                'jam_mulai' => '10:00:00',
                'jam_selesai' => '12:30:00',
                'ruangan' => 'Ruang Teori 301',
                'kuota' => 40,
            ],
            [
                'kode_mk' => 'TIF704',
                'nama_mk' => 'Cloud Computing & DevOps',
                'sks' => 3,
                'semester' => '7',
                'hari' => 'Rabu',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '15:30:00',
                'ruangan' => 'Lab Komputer 3',
                'kuota' => 30,
            ],
            [
                'kode_mk' => 'TIF705',
                'nama_mk' => 'Metodologi Riset & Penulisan Ilmiah',
                'sks' => 2,
                'semester' => '7',
                'hari' => 'Kamis',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '09:40:00',
                'ruangan' => 'Ruang Teori 205',
                'kuota' => 45,
            ],
            [
                'kode_mk' => 'TIF706',
                'nama_mk' => 'Etika Profesi & Hukum Siber',
                'sks' => 2,
                'semester' => '7',
                'hari' => 'Jumat',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '09:40:00',
                'ruangan' => 'Ruang Teori 202',
                'kuota' => 45,
            ],
            [
                'kode_mk' => 'TIF707',
                'nama_mk' => 'Proyek Perangkat Lunak Terintegrasi',
                'sks' => 4,
                'semester' => '7',
                'hari' => 'Kamis',
                'jam_mulai' => '10:00:00',
                'jam_selesai' => '13:20:00',
                'ruangan' => 'Studio Software',
                'kuota' => 25,
            ],
            [
                'kode_mk' => 'TIF708',
                'nama_mk' => 'Big Data & Sains Data',
                'sks' => 3,
                'semester' => '7',
                'hari' => 'Rabu',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:30:00',
                'ruangan' => 'Lab Komputer 4',
                'kuota' => 30,
            ],
        ];

        foreach ($mataKuliah as $mk) {
            MataKuliah::updateOrCreate(
                ['kode_mk' => $mk['kode_mk']],
                $mk
            );
        }
    }
}
