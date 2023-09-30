<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prosem extends Model
{
    use HasFactory;

    protected $fillable = [
        'guru_id',
        'mapel_id',
        'rombel_id',
        'prota_id',
        'semester', 
        'tanggal'
    ];

    function prota() {
        return $this->belongsTo(Prota::class);
    }

    function guru() {
        return $this->belongsTo(Guru::class, 'guru_id', 'nip');
    }

    function rombel() {
        return $this->belongsTo(Rombel::class,'rombel_id','kode');
    }

    function mapel() {
        return $this->belongsTo(Mapel::class, 'mapel_id', 'kode');
    }
}
