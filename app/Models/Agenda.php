<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'description',
        'start',
        'end',
        'location',
        'color',
        'user_id',
        'is_done',
        'is_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','userid');
    }
}
