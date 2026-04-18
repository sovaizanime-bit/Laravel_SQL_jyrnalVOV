<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Point;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем админа
        $admin = User::create([
            'name' => 'Администратор',
            'email' => 'admin@voen.ru',
            'password' => Hash::make('admin123'),
        ]);

        Point::create([
            'user_id' => $admin->id,
            'balance' => 999999 // безлимит для админа
        ]);

        // Создаем тестового пользователя
        $user = User::create([
            'name' => 'Иван Петров',
            'email' => 'user@voen.ru',
            'password' => Hash::make('user123'),
        ]);

        Point::create([
            'user_id' => $user->id,
            'balance' => 500
        ]);
    }
}
