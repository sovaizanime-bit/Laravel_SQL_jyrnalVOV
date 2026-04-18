<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;

class TestArticlesSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'user@voen.ru')->first();
        $admin = User::where('email', 'admin@voen.ru')->first();

        // Бесплатные статьи
        Article::create([
            'title' => 'Начало Великой Отечественной войны',
            'content' => '22 июня 1941 года... Подробное описание первых дней войны...',
            'price' => 0,
            'user_id' => $admin->id
        ]);

        Article::create([
            'title' => 'Битва за Москву',
            'content' => '30 сентября 1941 года началась битва за Москву...',
            'price' => 0,
            'user_id' => $admin->id
        ]);

        // Платные статьи
        Article::create([
            'title' => 'Сталинградская битва (полное исследование)',
            'content' => '17 июля 1942 года - 2 февраля 1943 года. Полная хронология событий, карты, документы...',
            'price' => 100,
            'user_id' => $admin->id
        ]);

        Article::create([
            'title' => 'Курская дуга: секретные архивы',
            'content' => 'Рассекреченные документы о подготовке и ходе Курской битвы...',
            'price' => 150,
            'user_id' => $admin->id
        ]);

        Article::create([
            'title' => 'Партизанское движение',
            'content' => 'Организация партизанских отрядов, их роль в победе...',
            'price' => 80,
            'user_id' => $user->id
        ]);
    }
}
