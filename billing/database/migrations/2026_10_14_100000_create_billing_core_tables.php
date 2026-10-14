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
        // 1. Mahasiswa Reference (Audit/Sync dari Akademik)
        Schema::create('mahasiswa_ref', function (Blueprint $table) {
            $table->string('nim', 20)->primary();
            $table->string('nama', 150);
            $table->string('prodi', 100);
            $table->string('angkatan', 4);
            $table->timestamps();
        });

        // 2. Tagihan Pembayaran (UKT, Wisuda, Denda)
        Schema::create('tagihan_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_invoice', 50)->unique();
            $table->string('nim', 20);
            $table->enum('jenis_tagihan', ['UKT', 'WISUDA', 'DENDA']);
            $table->string('deskripsi', 150)->nullable();
            $table->string('semester', 2)->nullable();
            $table->decimal('jumlah_tagihan', 12, 2);
            $table->string('nomor_va', 40)->unique();
            $table->enum('status_pembayaran', ['UNPAID', 'PAID', 'EXPIRED'])->default('UNPAID');
            $table->dateTime('tanggal_jatuh_tempo');
            $table->dateTime('tanggal_bayar')->nullable();
            $table->string('channel_bayar', 50)->nullable(); // e.g. M-BANKING SIMULATOR, TELLER
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswa_ref')->onDelete('cascade');
        });

        // 3. Webhook Logs (Audit Dispatch Event ke SIMAK)
        Schema::create('log_webhook_event', function (Blueprint $table) {
            $table->id();
            $table->string('event_name', 50);
            $table->string('target_url', 255);
            $table->text('payload');
            $table->unsignedSmallInteger('response_code')->nullable();
            $table->text('response_body')->nullable();
            $table->boolean('is_success')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_webhook_event');
        Schema::dropIfExists('tagihan_pembayaran');
        Schema::dropIfExists('mahasiswa_ref');
    }
};
