<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаём тестового пользователя
        if (!\App\Models\User::where('email', 'test@example.com')->exists()) {
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
        // Запускаем сидер для статусов
        $this->call([
            StatusSeeder::class,
            CategorySeeder::class,
        ]);
    }
}
