<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use HasFactory;
    protected $fillable = [
        'siswa_id',
        'kegiatan',
        'tingkat',
        'peringkat',
        'foto',
        'deskripsi',
        'tanggal'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id','nisn');
    }
}
