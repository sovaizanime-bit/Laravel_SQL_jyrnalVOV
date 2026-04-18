@extends('layouts.app')

@section('title', 'Корзина')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h2 class="mb-0">Корзина</h2>
            </div>
            <div class="card-body">
                @if($cartItems->count() > 0)
                    @foreach($cartItems as $item)
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5>{{ $item->article->title }}</h5>
                                        <p class="text-muted">Цена: {{ $item->article->price }} баллов</p>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <form action="{{ route('cart.remove', $item) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-center py-4">Корзина пуста</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-warning">
                <h4 class="mb-0">Итого</h4>
            </div>
            <div class="card-body">
                <p>Товаров: <strong>{{ $cartItems->count() }}</strong></p>
                <p>Сумма: <strong>{{ $total }} баллов</strong></p>
                <p>Ваш баланс: <strong>{{ $balance }} баллов</strong></p>
                
                @if($cartItems->count() > 0)
                    @if($balance >= $total)
                        <form action="{{ route('cart.pay') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">Оплатить</button>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            Недостаточно баллов!<br>
                            Нужно еще {{ $total - $balance }} баллов
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
