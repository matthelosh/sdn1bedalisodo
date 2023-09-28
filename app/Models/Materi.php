<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $fillable = [
        'tingkat',
        'mapel_id',
        'bab',
        'pokok',
        'ringkasan'
    ];

    function protas() {
        return $this->hasMany(Prota::class);
    }
}
