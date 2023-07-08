<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanBos extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode',
        'uraian',
        'parent_id'
    ];

    function parent() {
        return $this->belongsTo(KegiatanBos::class, 'parent_id', 'kode');
    }
    function children() {
        return $this->hasMany(KegiatanBos::class, 'parent_id', 'kode');
    }
}
