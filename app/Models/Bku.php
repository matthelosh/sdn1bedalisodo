<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bku extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'kode_kegiatan',
        'kode_rekening',
        'no_bukti',
        'uraian',
        'debet',
        'kredit',
        'saldo'
    ];
}
