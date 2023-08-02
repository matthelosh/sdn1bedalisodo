<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekskul extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode',
        'tapel_id',
        'label',
        'guru_id',
        'keterangan'
    ];

    private function tapel() {
        return $this->belongsTo(Tapel::class, 'tapel_id', 'kode');
    }

    private function guru() {
        return $this->belongsTo(Guru::class, 'guru_id', 'nip');
    }
}
