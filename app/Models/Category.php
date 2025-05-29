<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    // Разрешённые к массовому заполнению поля
    protected $fillable = ['name', 'type', 'user_id'];

    // Связь категория -> пользователь (многие к одному)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Связь категория -> транзакции (один ко многим)
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

