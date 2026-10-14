<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagihanPembayaran extends Model
{
    protected $table = 'tagihan_pembayaran';

    protected $fillable = [
        'nomor_invoice',
        'nim',
        'jenis_tagihan',
        'deskripsi',
        'semester',
        'jumlah_tagihan',
        'nomor_va',
        'status_pembayaran',
        'tanggal_jatuh_tempo',
        'tanggal_bayar',
        'channel_bayar',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_jatuh_tempo' => 'datetime',
            'tanggal_bayar' => 'datetime',
            'jumlah_tagihan' => 'decimal:2',
        ];
    }

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaRef::class, 'nim', 'nim');
    }
}
