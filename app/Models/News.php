<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = ['user_id', 'description', 'image'];

    // protected $casts = [
    //     'image' => 'array',
    // ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
