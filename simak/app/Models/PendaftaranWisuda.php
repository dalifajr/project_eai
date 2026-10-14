<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranWisuda extends Model
{
    protected $table = 'pendaftaran_wisuda';

    protected $fillable = [
        'nim',
        'tanggal_daftar',
        'invoice_wisuda_id',
        'nomor_va',
        'nominal_wisuda',
        'status_pembayaran',
        'status_pendaftaran',
        'ukuran_toga',
        'jumlah_undangan',
        'file_foto',
        'catatan',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }
}
