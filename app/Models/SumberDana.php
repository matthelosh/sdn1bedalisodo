<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SumberDana extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'tahun_anggaran',
        'sumber',
        'label',
        'tahap',
        'jumlah',
        'saldo',
        'keterangan',
        'status'
    ];

    function anggarans() {
        return $this->hasMany(Anggaran::class, 'sumber_dana', 'kode');
    }
}
