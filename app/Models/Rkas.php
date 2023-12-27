<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rkas extends Model
{
    use HasFactory;

    protected $fillable = [
        'anggaran_id',
        'bulan',
        'tahun',
        'status',
        'kode_rekening',
        'kode_kegiatan',
        'uraian',
        'volume',
        'sumber',
        'satuan',
        'tarif',
        'jumlah'
    ];

    function anggaran() {
        return $this->belongsTo(Anggaran::class, 'anggaran_id', 'kode');
    }
}
