<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atp extends Model
{
    use HasFactory;

    protected $fillable = [
        'alur',
        'mapel_id',
        'tingkat',
        'teks',
        'fase',
        'elemen',
        'jp',
        'keywords',
        'ppp',
        'glosarium'
    ];

    function rombel() {
        return $this->belongsTo(Rombel::class, 'tingkat','tingkat');
    }
}
