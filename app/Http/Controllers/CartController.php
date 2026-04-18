<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = auth()->user()->cart()
            ->with('article')
            ->where('is_paid', false)
            ->get();
            
        $total = $cartItems->sum(function($item) {
            return $item->article->price;
        });
        
        $balance = auth()->user()->point?->balance ?? 0;

        return view('cart.index', compact('cartItems', 'total', 'balance'));
    }

    public function add(Article $article)
    {
        $exists = auth()->user()->cart()
            ->where('article_id', $article->id)
            ->where('is_paid', false)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Статья уже в корзине');
        }

        auth()->user()->cart()->create([
            'article_id' => $article->id,
            'is_paid' => false
        ]);

        return back()->with('success', 'Статья добавлена в корзину');
    }

    public function remove(Cart $cart)
    {
        if ($cart->user_id != auth()->id()) {
            abort(403);
        }

        $cart->delete();
        return back()->with('success', 'Статья удалена из корзины');
    }

    public function pay(Request $request)
    {
        $cartItems = auth()->user()->cart()
            ->with('article')
            ->where('is_paid', false)
            ->get();
            
        $total = $cartItems->sum(function($item) {
            return $item->article->price;
        });
        
        $balance = auth()->user()->point?->balance ?? 0;

        if ($balance < $total) {
            return back()->with('error', 'Недостаточно баллов! Обратитесь к администратору');
        }

        // Имитация оплаты
        return view('cart.payment', compact('cartItems', 'total'));
    }

    public function processPayment(Request $request)
    {
        $cartItems = auth()->user()->cart()
            ->where('is_paid', false)
            ->get();
            
        $total = $cartItems->sum(function($item) {
            return $item->article->price;
        });
        
        $point = auth()->user()->point;
        
        if (!$point || $point->balance < $total) {
            return redirect()->route('cart.index')->with('error', 'Недостаточно баллов!');
        }

        // Списываем баллы
        $point->balance -= $total;
        $point->save();

        // Помечаем статьи как оплаченные
        foreach ($cartItems as $item) {
            $item->is_paid = true;
            $item->save();
        }

        return redirect()->route('home')->with('success', 'Оплатили! Спасибо за покупку');
    }
}
