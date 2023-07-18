<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_surat',
        'klasifikasi_id',
        'tanggal_surat',
        'perihal',
        'tipe',
        'sifat',
        'lingkup',
        'pengirim',
        'alamat',
        'ringkasan',
        'file_surat'
    ];

    function klasifikasi() {
        return $this->belongsTo(KlasifikasiSurat::class, 'klasifikasi_id', 'kode');
    }
}
