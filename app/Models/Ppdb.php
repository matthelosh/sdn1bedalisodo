<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ppdb extends Model
{
    use HasFactory;
    protected $fillable = [
        'tapel',
        'nisn',
        'nik',
        'nama',
        'jk',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'rt',
        'rw',
        'desa',
        'kecamatan',
        'kabupaten',
        'kode_pos',
        'hp',
        'email',
        'asal_tk',
        'nama_ayah',
        'nik_ayah',
        'nama_ibu',
        'nik_ibu',
        'nama_wali',
        'nik_wali',
        'hubungan_wali'
    ];
}
