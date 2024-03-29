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
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Category::class,  'parent_id', 'id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'kategori_id', 'kode');
    }
}
