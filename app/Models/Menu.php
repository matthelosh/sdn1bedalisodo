<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'url',
        'icon',
        'roles',
        'status',
        'parent_id'
    ];

    function parent() {
        return $this->belongsTo(Menu::class, 'parent_id', 'id');
    }

    function children() {
        return $this->hasMany(Menu::class, 'parent_id','id');
    }
}
