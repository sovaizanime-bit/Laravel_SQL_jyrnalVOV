@extends('layouts.app')

@section('title', 'Написать статью')

@section('content')
<div class="container mt-4">
    <h2>Написать статью</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf 

        <div class="mb-3">
            <label for="title" class="form-label">Заголовок</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Содержание статьи</label>
            <textarea name="content" id="content" rows="10" class="form-control" required>{{ old('content') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Стоимость</label>
            <input type="number" name="price" id="price" class="form-control" value="{{ old('price', 0) }}" min="0" required>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Обложка статьи (необязательно)</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Опубликовать статью</button>
    </form>
</div>
@endsection