<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    use HasFactory;

    protected $fillable = [
        'npsn',
        'nss',
        'nama',
        'alamat',
        'rt',
        'rw',
        'desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'telp',
        'email',
        'website',
        'facebook',
        'youtube',
        'instagram',
        'motto',
        'ks',
        'bendahara',
        'operator'
    ];

    function ks() {
        return $this->belongsTo(Guru::class, 'ks', 'nip');
    }
    function bendahara() {
        return $this->belongsTo(Guru::class, 'bendahara', 'nip');
    }
    function operator() {
        return $this->belongsTo(Guru::class, 'operator', 'nip');
    }
}
