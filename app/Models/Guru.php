<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'nama',
        'jk',
        'tempat_lahir',
        'tanggal_lahir',
        'hp',
        'alamat',
        'role',
        'foto',
        'facebook',
        'youtube',
        'instagram',
        'status'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'userable_id', 'id');
    }

    function rombels() {
        return $this->hasMany(Rombel::class,"guru_id", "nip");
    }
}
