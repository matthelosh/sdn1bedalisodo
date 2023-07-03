<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $fillable = [
        'tipe',
        'jenis',
        'tanggal',
        'uraian',
        'kode_kegiatan',
        'kode_rekening',
        'no_bukti',
        'merchant',
        'nilai'
    ];

    function bku() {
        return $this->hasOne(Bku::class, 'kode_kegiatan', 'kode_kegiatan');
    }
}
