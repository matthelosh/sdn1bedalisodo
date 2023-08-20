<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupWa extends Model
{
    use HasFactory;

    protected $fillable = [
        'rombel_id',
        'chat_id',
        'label'
    ];

    function rombel() {
        return $this->belongsTo(Rombel::class, 'rombel_id', 'id');
    }
}
