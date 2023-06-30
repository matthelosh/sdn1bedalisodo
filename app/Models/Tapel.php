<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tapel extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'label',
        'status'
    ];

    function rombels() {
        return $this->hasMany(Rombel::class, 'tapel', 'kode');
    }
}
