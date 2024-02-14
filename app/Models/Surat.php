<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $fillable = [
        'klasifikasi_id',
        'no_surat',
        'kode',
        'kategori',
        'penerima',
        'perihal',
        'tanggal',
        'tembusan',
        'status',
    ];

    public function klasifikasi()
    {
        return $this->belongsTo(KlasifikasiSurat::class, 'klasifikasi_id', 'kode');
    }

    public function arsip()
    {
        return $this->hasOne(Arsip::class, 'surat_id', 'kode');
    }
}
