<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'tahun_anggaran',
        'sumber_dana',
        'tahap',
        'uraian',
        'nilai',
        'keterangan',
        'mulai',
        'selesai',
        'status'
    ];

    function transaksis() {
        return $this->hasMany(Transaksi::class, 'anggaran_id', 'kode');
    }

}
