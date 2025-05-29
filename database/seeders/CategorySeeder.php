<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            [
                'name' => 'Зарплата',
                'type' => 'income',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Продукты',
                'type' => 'expense',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Развлечения',
                'type' => 'expense',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Фриланс',
                'type' => 'income',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

