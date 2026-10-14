<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaRef extends Model
{
    protected $table = 'mahasiswa_ref';
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nim',
        'nama',
        'prodi',
        'angkatan',
    ];

    public function tagihanList()
    {
        return $this->hasMany(TagihanPembayaran::class, 'nim', 'nim');
    }
}
