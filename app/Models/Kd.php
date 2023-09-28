<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kd extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'mapel_id',
        'tingkat',
        'teks'
    ];

    function rombel() {
        return $this->belongsTo(Rombel::class, 'tingkat','tingkat');
    }
}
