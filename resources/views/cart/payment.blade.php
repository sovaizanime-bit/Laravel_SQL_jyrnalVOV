@extends('layouts.app')

@section('title', 'Подтверждение оплаты')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h3 class="mb-0">Подтверждение оплаты</h3>
            </div>
            <div class="card-body text-center">
                <h4 class="mb-4">Сумма к оплате: <strong>{{ $total }} баллов</strong></h4>
                
                <form action="{{ route('cart.process-payment') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-lg" onclick="alert('Оплатили! Спасибо за покупку')">
                        Подтвердить оплату
                    </button>
                    <a href="{{ route('cart.index') }}" class="btn btn-secondary btn-lg">Отмена</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
