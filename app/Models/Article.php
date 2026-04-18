<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'price', 'user_id', 'is_published', 'image'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function bans()
    {
        return $this->morphMany(Ban::class, 'bannable');
    }

    public function isFree()
    {
        return $this->price == 0;
    }

    public function isBanned()
    {
        return Ban::where('bannable_type', Article::class)
            ->where('bannable_id', $this->id)
            ->exists();
    }
}
