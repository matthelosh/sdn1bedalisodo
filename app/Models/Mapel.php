<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'label',
        'tingkat',
        'kurikulum',
        'kelompok',
        'kategori'
    ];

    function rombel() {
        return $this->belongsToMany(Rombel::class, 'mapel_rombel');
    }
}
