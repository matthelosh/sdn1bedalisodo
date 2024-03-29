<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KlasifikasiSurat extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'uraian'
    ];

    public function surats()
    {
        return $this->hasMany(Surat::class, 'klasifikasi_id', 'kode');
    }
}
