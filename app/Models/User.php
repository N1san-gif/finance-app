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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Связь один-ко-многим с категориями
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    // Связь один-ко-многим с транзакциями
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
