<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelajaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'tapel',
        'tingkat',
        'mapel_id',
        'bab'
    ];

    function rombel() {
        return $this->belongsTo(Rombel::class, 'tingkat','tingkat');
    }
}
