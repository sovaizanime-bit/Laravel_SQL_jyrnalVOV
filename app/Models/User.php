<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_blocked',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Проверка на админа
    public function isAdmin()
    {
        return $this->email === 'admin@voen.ru';
    }

    // Проверка на блокировку
    public function isBanned()
    {
        return Ban::where('bannable_type', User::class)
            ->where('bannable_id', $this->id)
            ->exists();
    }

    // Связи
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function point()
    {
        return $this->hasOne(Point::class);
    }

    public function bans()
    {
        return $this->morphMany(Ban::class, 'bannable');
    }
}
