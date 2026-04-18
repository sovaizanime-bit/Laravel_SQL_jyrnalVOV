<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Ban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        // 1. Создаем базовый запрос
        $query = Article::latest();

        // 2. Если пользователь НЕ админ (или не авторизован), прячем заблокированные
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            $query->where('is_blocked', false);
        }

        $articles = $query->paginate(10);

        return view('articles.index', compact('articles'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $articles = Article::where('is_published', true)
            ->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%");
            })
            ->latest()
            ->paginate(10);
            
        return view('articles.index', compact('articles', 'query'));
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Валидация картинки
        ]);

        // Создаем объект статьи, но пока не сохраняем
        $article = new Article([
            'title' => $request->title,
            'content' => $request->content,
            'price' => $request->price,
            'user_id' => Auth::id(),
            'is_published' => true,
        ]);

        // 📷 Сохраняем картинку, если она прикреплена
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public'); // Пишет в storage/app/public/articles
            $article->image = $path; // В базу пойдет: "articles/случайное_имя.jpg"
        }

        $article->save(); // Сохраняем всё в базу

        return redirect()->route('home')->with('success', 'Статья опубликована');
    }

    public function show(Article $article)
    {
        // Если статья заблокирована И пользователь НЕ админ — выдаем 404
        if ($article->is_blocked && (!auth()->check() || !auth()->user()->isAdmin())) {
            abort(404, 'Статья не найдена');
        }

        // Проверяем покупку платной статьи
        $isPaid = false;
        if (auth()->check() && $article->price > 0) {
            $isPaid = auth()->user()->cart()
                ->where('article_id', $article->id)
                ->where('is_paid', true)
                ->exists();
        }

        $comments = $article->comments()->get();

        return view('articles.show', compact('article', 'comments', 'isPaid'));
    }

    public function edit(Article $article)
    {
        // Только автор может редактировать
        if (auth()->id() != $article->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        return view('articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        if (auth()->id() != $article->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $article->title = $request->title;
        $article->content = $request->content;
        $article->price = $request->price;

        // 📷 Если загрузили новую картинку
        if ($request->hasFile('image')) {
            // Удаляем старую картинку с диска, если она была
            if ($article->image && Storage::disk('public')->exists($article->image)) {
                Storage::disk('public')->delete($article->image);
            }

            // Сохраняем новую
            $path = $request->file('image')->store('articles', 'public');
            $article->image = $path;
        }

        $article->save();

        return redirect()->route('home')->with('success', 'Статья обновлена');
    }

    public function destroy(Article $article)
    {
        // Только автор может удалить
        if (auth()->id() != $article->user_id && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $article->delete();
        
        return redirect()->route('home')->with('success', 'Статья удалена');
    }
    public function toggleBlock(Article $article)
    {
        // Проверяем, что это точно админ
        if (!auth()->user()->isAdmin()) {
            abort(403, 'У вас нет прав доступа');
        }

        // Переключаем статус (если было true станет false, и наоборот)
        $article->is_blocked = !$article->is_blocked;
        $article->save();

        $status = $article->is_blocked ? 'заблокирована' : 'разблокирована';
        
        return redirect()->back()->with('success', "Статья успешно {$status}!");
    }
}
