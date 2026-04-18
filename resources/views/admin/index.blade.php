@extends('layouts.app')

@section('title', 'Админ-панель')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h2 class="mb-0">Админ-панель</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card text-white bg-danger">
                            <div class="card-body">
                                <h4>Пользователи</h4>
                                <h2>{{ $users }}</h2>
                                <a href="{{ route('admin.users') }}" class="btn btn-light">Управление</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <h4>Статьи</h4>
                                <h2>{{ $articles }}</h2>
                                <a href="{{ route('admin.articles') }}" class="btn btn-light">Управление</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <h4>Комментарии</h4>
                                <h2>{{ $comments }}</h2>
                                <a href="{{ route('admin.comments') }}" class="btn btn-light">Модерация</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
