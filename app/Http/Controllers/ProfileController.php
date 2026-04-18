<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        $articles = Article::where('user_id', $user->id)
            ->latest()
            ->paginate(10);
        
        $comments = Comment::where('user_id', $user->id)
            ->with('article')
            ->latest()
            ->get();
        
        $balance = $user->point->balance ?? 0;
        
        $paidArticles = $user->cart()
            ->with('article')
            ->where('is_paid', true)
            ->get();
        
        return view('profile.dashboard', compact('user', 'articles', 'comments', 'balance', 'paidArticles'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        
        $user->update($request->only('name', 'email'));
        
        return redirect()->route('profile.dashboard')->with('success', 'Профиль обновлен');
    }
}
