<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'guru_id',
        'tapel',
        'mapel_id',
        'rombel_id',
        'jamke',
        'jml_jam',
        'hari'
    ];

    function rombel() {
        return $this->belongsTo(Rombel::class, 'rombel_id','kode');
    }

    function mapel() {
        return $this->belongsTo(Mapel::class, 'mapel_id', 'kode');
    }

    function guru() {
        return $this->belongsTo(Guru::class, 'guru_id','nip');
    }
}
