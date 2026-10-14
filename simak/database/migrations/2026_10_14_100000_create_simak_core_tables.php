<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Mahasiswa
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->string('nim', 20)->primary();
            $table->string('nama', 150);
            $table->string('program_studi', 100);
            $table->string('fakultas', 100);
            $table->string('angkatan', 4);
            $table->enum('status_akademik', ['aktif', 'cuti', 'non_aktif'])->default('aktif');
            $table->enum('status_skripsi', ['belum', 'proposal', 'sidang', 'lulus'])->default('belum');
            $table->boolean('bebas_lab')->default(false);
            $table->boolean('bebas_perpustakaan')->default(false);
            $table->decimal('ips_lalu', 3, 2)->default(3.50);
            $table->unsignedSmallInteger('batas_sks')->default(24);
            $table->string('semester_sekarang', 2)->default('7');
            $table->timestamps();
        });

        // 2. Mata Kuliah & Jadwal
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->string('kode_mk', 20)->primary();
            $table->string('nama_mk', 150);
            $table->unsignedTinyInteger('sks');
            $table->string('semester', 2);
            $table->string('hari', 15); // e.g. Senin, Selasa
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruangan', 50)->default('Lab 1');
            $table->unsignedSmallInteger('kuota')->default(40);
            $table->timestamps();
        });

        // 3. KRS
        Schema::create('krs', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 20);
            $table->string('semester', 2);
            $table->string('tahun_akademik', 9)->default('2026/2027');
            $table->unsignedSmallInteger('total_sks')->default(0);
            $table->enum('status_krs', ['draft', 'disetujui'])->default('draft');
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
        });

        // 4. KRS Detail
        Schema::create('krs_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('krs_id')->constrained('krs')->onDelete('cascade');
            $table->string('kode_mk', 20);
            $table->timestamps();

            $table->foreign('kode_mk')->references('kode_mk')->on('mata_kuliah')->onDelete('cascade');
        });

        // 5. Pendaftaran Wisuda
        Schema::create('pendaftaran_wisuda', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 20);
            $table->date('tanggal_daftar');
            $table->string('invoice_wisuda_id', 50)->nullable();
            $table->string('nomor_va', 50)->nullable();
            $table->decimal('nominal_wisuda', 12, 2)->default(1750000);
            $table->enum('status_pembayaran', ['unpaid', 'paid'])->default('unpaid');
            $table->enum('status_pendaftaran', ['menunggu_pembayaran', 'berkas_lengkap', 'disetujui'])->default('menunggu_pembayaran');
            $table->enum('ukuran_toga', ['S', 'M', 'L', 'XL', 'XXL'])->nullable();
            $table->unsignedTinyInteger('jumlah_undangan')->default(2);
            $table->string('file_foto')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_wisuda');
        Schema::dropIfExists('krs_detail');
        Schema::dropIfExists('krs');
        Schema::dropIfExists('mata_kuliah');
        Schema::dropIfExists('mahasiswa');
    }
};
