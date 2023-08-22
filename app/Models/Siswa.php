<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    protected $fillable = [
        'nisn',
        'nis',
        'nama',
        'jk',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat',
        'rt',
        'rw',
        'desa',
        'kecamatan',
        'kode_pos',
        'kabupaten',
        'angkatan',
        'hp',
        'email',
        'nik',
        'is_active',
        'status',
        'foto_url',
        'no_akta',
        'sekolah_asal',
        'no_kk'
    ];

    function rombel()  {
        return $this->belongsToMany(Rombel::class, "rombel_siswa");
    }

    function alumni() {
        return $this->hasOne(Alumni::class, 'nisn','nisn');
    }

    function ortu() {
        return $this->belongsTo(Ortu::class, 'ortu_id', 'id');
    }

    function ekskuls() {
        return $this->belongsToMany(Ekskul::class, 'ekskul_siswa');
    }
}
