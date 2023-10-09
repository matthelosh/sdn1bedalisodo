<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'rombel_id',
        'mapel_id',
        'semester',
        'periode',
        'tanggal'
    ];

    function rombel() {
        return $this->belongsTo(Rombel::class, 'rombel_id', 'kode');
    }

    function mapel() {
        return $this->belongsTo(Mapel::class, 'mapel_id', 'kode');
    }
}
