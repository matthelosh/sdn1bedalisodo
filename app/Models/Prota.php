<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prota extends Model
{
    use HasFactory;
    protected $fillable = [
        'alur',
        'guru_id',
        'rombel_id',
        'mapel_id',
        'atp_id',   
        'materi_id',
        'jp',
        'semester',
        'tingkat'
    ];

    function guru() {
        return $this->belongsTo(Guru::class, 'guru_id', 'nip');
    }

    function rombel() {
        return $this->belongsTo(Rombel::class, 'rombel_id', 'kode');
    }

    function atp() {
        return $this->belongsTo(Atp::class);
    }

    function materi() {
        return $this->belongsTo(Materi::class);
    }
}
