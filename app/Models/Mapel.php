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
        'kurikulum'
    ];

    function rombel() {
        return $this->belongsToMany(Rombel::class, 'mapel_rombel');
    }
}
