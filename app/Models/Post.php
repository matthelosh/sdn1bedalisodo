<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'author_id',
        'category_id',
        'slug',
        'title',
        'featured_image',
        'content',
        'status',
        'starred',
        'abstract'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'name');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'kode');
    }

    // public function comments()
    // {
    //     return $this->hasMany(Comment::class);
    // }

    function views() {
        return $this->hasMany(ViewCounter::class, 'post_id', 'id');
    }
}
