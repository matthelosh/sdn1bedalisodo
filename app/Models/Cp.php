<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cp extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'fase',
        'tingkat',
        'elemen',
        'teks',
        'mapel_id'
    ];

    function mapel() {
        return $this->belongsTo(Mapel::class, 'mapel_id', 'kode');
    }
}
