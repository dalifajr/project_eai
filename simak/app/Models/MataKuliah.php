<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliah';
    protected $primaryKey = 'kode_mk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'kuota',
    ];

    public function krsDetails()
    {
        return $this->hasMany(KrsDetail::class, 'kode_mk', 'kode_mk');
    }
}
