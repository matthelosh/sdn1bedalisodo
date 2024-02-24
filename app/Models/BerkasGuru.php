<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasGuru extends Model
{
    use HasFactory;
    protected $fillable = [
        'guru_id',
        'tipe',
        // 'kode',
        'label',
        'deskripsi',
        'keterangan',
        'url'
    ];

    private function guru() {
        return $this->belongsTo(Guru::class, 'guru_id', 'nip'); 
    }
}
