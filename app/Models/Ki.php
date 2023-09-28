<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ki extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'tingkat',
        'mapel_id',
        'teks'
    ];

    function rombel() {
        return $this->belongsTo(Rombel::class, 'tingkat', 'tingkat');
    }
}
