<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dana extends Model
{
    use HasFactory;
    protected $fillable = [
        'tahap',
        'tanggal',
        'uraian',
        'tahun_anggaran',
        'sumber',
        'masuk',
        'saldo',
        'keterangan',
        'user_id'
    ];

    function user() {
        return $this->belongsTo(User::class, 'user_id', 'name');
    }
}
