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
}
