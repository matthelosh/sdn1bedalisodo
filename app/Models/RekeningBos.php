<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningBos extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode',
        'rincian_obyek',
        'ppn',
        'pph21',
        'pph23',
        'pph4',
        'sspd',
        'blok_belanja',
    ];
}
