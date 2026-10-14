<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nim',
        'nama',
        'program_studi',
        'fakultas',
        'angkatan',
        'status_akademik',
        'status_skripsi',
        'bebas_lab',
        'bebas_perpustakaan',
        'ips_lalu',
        'batas_sks',
        'semester_sekarang',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'nim', 'nim');
    }

    public function krsList()
    {
        return $this->hasMany(Krs::class, 'nim', 'nim');
    }

    public function pendaftaranWisuda()
    {
        return $this->hasOne(PendaftaranWisuda::class, 'nim', 'nim')->latestOfMany();
    }
}
