<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Elemen extends Model
{
    use HasFactory;

    protected $fillable = [
        'fase',
        'mapel_id',
        'name',
        'teks'
    ];

    function mapel() {
        return $this->belongsTo(Mapel::class, 'mapel_id','kode');
    }
}
