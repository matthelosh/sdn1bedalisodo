<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_anggaran',
        'sumber_dana',
        'bulan_anggaran',
        'tahap',
        'kode_rekening',
        'kode_kegiatan',
        'uraian',
        'volume',
        'satuan',
        'tarif',
        'keterangan'
    ];
}
