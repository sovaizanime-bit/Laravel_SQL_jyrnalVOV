@extends('layouts.app')

@section('title', 'Модерация статей')

@section('content')
<div class="card">
    <div class="card-header bg-danger text-white">
        <h2 class="mb-0">Модерация статей</h2>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Автор</th>
                        <th>Дата</th>
                        <th>Цена</th>
                        <th>Комментарии</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                        <tr>
                            <td>{{ $article->id }}</td>
                            <td>
                                <a href="{{ route('articles.show', $article) }}" target="_blank" class="text-decoration-none fw-bold text-dark">
                                    {{ Str::limit($article->title, 30) }}
                                </a>
                            </td>
                            <td>{{ $article->user->name }}</td>
                            <td>{{ $article->created_at->format('d.m.Y') }}</td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    {{ $article->price > 0 ? $article->price . ' баллов' : 'Бесплатно' }}
                                </span>
                            </td>
                            <td>
                                <i class="bi bi-chat-dots"></i> {{ $article->comments->count() }}
                            </td>
                            <td>
                                @if($article->is_blocked)
                                    <span class="badge bg-danger">Заблокирована</span>
                                @else
                                    <span class="badge bg-success">Активна</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('articles.toggle-block', $article) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $article->is_blocked ? 'btn-success' : 'btn-danger' }}"
                                            onclick="return confirm('{{ $article->is_blocked ? 'Разблокировать статью?' : 'Заблокировать статью?' }}')">
                                        <i class="bi {{ $article->is_blocked ? 'bi-check-circle' : 'bi-slash-circle' }}"></i>
                                        {{ $article->is_blocked ? 'Разблокировать' : 'Заблокировать' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Статей пока нет</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $articles->links() }}
        </div>
    </div>
</div>
@endsection