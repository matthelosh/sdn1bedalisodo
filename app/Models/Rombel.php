<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    use HasFactory;
    protected $fillable = [
        'tapel',
        "kode",
        "label",
        "kurikulum",
        "tingkat",
        "guru_id" 
    ];

    function guru() {
        return $this->belongsTo(Guru::class, "guru_id", "nip");
    }

    function tapel() {
        return $this->belongsTo(Tapel::class, "tapel","kode");   
    }

    function siswas() {
        return $this->belongsToMany(Siswa::class);
    }
}
