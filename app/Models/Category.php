<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = [
        'kode',
        'label',
        'parent_id'
    ];

    public function parent()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function children()
    {
        return $this->hasMany(Kategori::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'kategori_id', 'kode');
    }
}
