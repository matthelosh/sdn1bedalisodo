<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bukti extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'label',
        'url',
        'tipe'
    ];

    function transaksi() {
        return $this->belongsTo(Transaksi::class, 'transaksi_id', 'no_bukti');
    }
}
