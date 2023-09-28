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

    function mapels() {
        return $this->belongsToMany(Mapel::class, 'mapel_rombel');
    }
    function guru() {
        return $this->belongsTo(Guru::class, "guru_id", "nip");
    }

    function tapel() {
        return $this->belongsTo(Tapel::class, "tapel","kode");   
    }

    function siswas() {
        return $this->belongsToMany(Siswa::class);
    }

    function jadwals() {
        return $this->hasMany(Jadwal::class, 'rombel_id', 'kode');
    }

    function grupwa() {
        return $this->hasOne(GrupWa::class, 'rombel_id','id');
    }

    function atps() {
        return $this->hasMany(Atp::class, 'tingkat', 'tingkat');
    }
    function pebelajarans() {
        return $this->hasMany(Pembelajaran::class, 'tingkat', 'tingkat');
    }

    public static function boot() {
        parent::boot();
        self::deleting(function($rombel) {
            $rombel->siswas()->detach();
        });
    }

    

}
