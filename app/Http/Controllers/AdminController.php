<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Point;
use App\Models\Ban;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::count();
        $articles = Article::count();
        $comments = Comment::count();
        
        return view('admin.index', compact('users', 'articles', 'comments'));
    }

    public function users()
    {
        $users = User::with('point')->paginate(20);
        return view('admin.users', compact('users'));
    }

    // ✅ НОВЫЙ ИСПРАВЛЕННЫЙ МЕТОД ДЛЯ КНОПКИ БЛОКИРОВКИ ПОЛЬЗОВАТЕЛЯ
    public function toggleBlockUser(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Нельзя заблокировать администратора!');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Нельзя заблокировать самого себя!');
        }

        // Переключаем статус (если было false/0, станет true/1, и наоборот)
        $user->is_blocked = !$user->is_blocked;
        $user->save();

        $status = $user->is_blocked ? 'заблокирован' : 'разблокирован';

        return back()->with('success', "Пользователь {$user->name} успешно {$status}!");
    }

    public function addPoints(Request $request, User $user)
    {
        $request->validate(['points' => 'required|integer|min:1']);
        
        $point = $user->point ?? Point::create(['user_id' => $user->id, 'balance' => 0]);
        $point->balance += $request->points;
        $point->save();

        return back()->with('success', "Выдано {$request->points} баллов");
    }

    public function removePoints(Request $request, User $user)
    {
        $request->validate(['points' => 'required|integer|min:1']);
        
        $point = $user->point;
        if (!$point) {
            return back()->with('error', 'У пользователя нет баллов');
        }

        if ($request->points >= $point->balance) {
            $point->balance = 0;
        } else {
            $point->balance -= $request->points;
        }
        $point->save();

        return back()->with('success', "Забрано {$request->points} баллов");
    }

    public function articles()
    {
        $articles = Article::with('user')->paginate(20);
        return view('admin.articles', compact('articles'));
    }

    public function comments()
    {
        $comments = Comment::with('user', 'article')
            ->withExists(['bans as is_banned' => function($query) {
                $query->where('bannable_type', Comment::class);
            }])
            ->paginate(20);
            
        return view('admin.comments', compact('comments'));
    }

    public function banComment(Comment $comment)
    {
        $exists = Ban::where('bannable_type', Comment::class)
            ->where('bannable_id', $comment->id)
            ->exists();

        if (!$exists) {
            Ban::create([
                'user_id' => auth()->id(),
                'bannable_type' => Comment::class,
                'bannable_id' => $comment->id
            ]);
            
            return back()->with('success', 'Комментарий заблокирован');
        }

        return back()->with('error', 'Комментарий уже заблокирован');
    }

    public function unbanComment(Comment $comment)
    {
        Ban::where('bannable_type', Comment::class)
            ->where('bannable_id', $comment->id)
            ->delete();

        return back()->with('success', 'Комментарий разблокирован');
    }

    public function report()
    {
        return view('admin.report');
    }
}