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
        'gelar_depan',
        'gelar_belakang',
        'jk',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'hp',
        'alamat',
        'role',
        'foto',
        'facebook',
        'youtube',
        'instagram',
        'status',
        'nickname',
        'bio',
        'pangkat'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'userable_id', 'id');
    }

    function rombels() {
        return $this->hasMany(Rombel::class,"guru_id", "nip");
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'guru_id', 'nip');
    }
}
