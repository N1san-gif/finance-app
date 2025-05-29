<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    // Если нужно, укажи, какие поля можно массово заполнять
    protected $fillable = ['name'];

    // Если хочешь связать статусы с транзакциями, добавь связь
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
