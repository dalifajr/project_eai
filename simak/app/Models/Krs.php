<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    protected $table = 'krs';

    protected $fillable = [
        'nim',
        'semester',
        'tahun_akademik',
        'total_sks',
        'status_krs',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'nim', 'nim');
    }

    public function details()
    {
        return $this->hasMany(KrsDetail::class, 'krs_id', 'id');
    }
}
