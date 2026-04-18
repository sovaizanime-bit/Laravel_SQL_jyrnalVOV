<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

// 🌍 Публичные маршруты (для всех)
Route::get('/', [ArticleController::class, 'index'])->name('home');
Route::get('/search', [ArticleController::class, 'search'])->name('articles.search');

// 🔐 Маршруты для авторизованных пользователей
Route::middleware('auth')->group(function () {

    // Личный кабинет и Профиль
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Статьи (создание, просмотр, редактирование, удаление)
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
    
    // 🛑 НАШ ОБЩИЙ МАРШРУТ БЛОКИРОВКИ (ВЫНЕСЛИ СЮДА!)
    Route::post('/articles/{article}/toggle-block', [ArticleController::class, 'toggleBlock'])->name('articles.toggle-block');

    // Комментарии
    Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Корзина и Оплата
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{article}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/pay', [CartController::class, 'pay'])->name('cart.pay');
    Route::post('/cart/process-payment', [CartController::class, 'processPayment'])->name('cart.process-payment');
});

// 👑 Админ-панель (только для админов)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    
    // Модерация Пользователей
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/ban', [AdminController::class, 'banUser'])->name('users.ban');
    Route::post('/users/{user}/unban', [AdminController::class, 'unbanUser'])->name('users.unban');
    Route::post('/users/{user}/points', [AdminController::class, 'addPoints'])->name('users.points');
    Route::post('/users/{user}/points/remove', [AdminController::class, 'removePoints'])->name('users.points.remove');
    Route::post('/users/{user}/toggle-block', [AdminController::class, 'toggleBlockUser'])->name('users.toggle-block');
    
    // Модерация Статей
    Route::get('/articles', [AdminController::class, 'articles'])->name('articles');

    // Модерация Комментариев
    Route::get('/comments', [AdminController::class, 'comments'])->name('comments');
    Route::post('/comments/{comment}/ban', [AdminController::class, 'banComment'])->name('comments.ban');
    Route::post('/comments/{comment}/unban', [AdminController::class, 'unbanComment'])->name('comments.unban');

    Route::get('/report', [AdminController::class, 'report'])->name('report');
    
});

require __DIR__.'/auth.php';