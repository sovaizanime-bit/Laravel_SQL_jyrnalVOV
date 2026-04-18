<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Article;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $request->validate([
            'content' => 'required|min:3'
        ]);

        Comment::create([
            'content' => $request->content,
            'user_id' => auth()->id(),
            'article_id' => $article->id
        ]);

        return back()->with('success', 'Комментарий добавлен');
    }

    public function update(Request $request, Comment $comment)
    {
        // Проверяем, что пользователь - автор комментария
        if (auth()->id() != $comment->user_id) {
            abort(403);
        }

        $request->validate([
            'content' => 'required|min:3'
        ]);

        $comment->update([
            'content' => $request->content
        ]);

        return back()->with('success', 'Комментарий обновлен');
    }

    public function destroy(Comment $comment)
    {
        // Проверяем, что пользователь - автор комментария
        if (auth()->id() != $comment->user_id) {
            abort(403);
        }

        $comment->delete();
        
        return back()->with('success', 'Комментарий удален');
    }
}
