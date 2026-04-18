@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0">Мой профиль</h4>
            </div>
            <div class="card-body">
                <p><strong>Имя:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Роль:</strong> {{ $user->isAdmin() ? 'Администратор' : 'Пользователь' }}</p>
                <p><strong>Баллы:</strong> 
                    @if($user->isAdmin())
                        <span class="badge bg-warning">Безлимитно</span>
                    @else
                        <span class="badge bg-success">{{ $balance }}</span>
                    @endif
                </p>
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-danger w-100">Редактировать</a>
            </div>
        </div>
        
        @if($paidArticles->count() > 0)
        <div class="card mt-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Купленные статьи</h5>
            </div>
            <div class="card-body">
                @foreach($paidArticles as $item)
                    <div class="mb-2">
                        <a href="{{ route('articles.show', $item->article) }}">
                            {{ Str::limit($item->article->title, 30) }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
    
    <div class="col-md-8">
        <!-- Мои статьи -->
        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Мои статьи</h5>
            </div>
            <div class="card-body">
                @if($articles->count() > 0)
                    @foreach($articles as $article)
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5>{{ $article->title }}</h5>
                                        <p class="text-muted small">
                                            {{ $article->comments->count() }} комментариев | 
                                            {{ $article->price > 0 ? $article->price . ' баллов' : 'Бесплатно' }}
                                        </p>
                                    </div>
                                    <div>
                                        <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-warning">✏️</a>
                                        <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить статью?')">🗑️</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    {{ $articles->links() }}
                @else
                    <p class="text-center">У вас пока нет статей</p>
                    <a href="{{ route('articles.create') }}" class="btn btn-danger w-100">Написать статью</a>
                @endif
            </div>
        </div>
        
        <!-- Мои комментарии -->
        @if($comments->count() > 0)
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Мои комментарии</h5>
            </div>
            <div class="card-body">
                @foreach($comments as $comment)
                    <div class="comment mb-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ Str::limit($comment->article->title, 50) }}</strong>
                                <p class="mb-1">{{ Str::limit($comment->content, 100) }}</p>
                                <small class="text-muted">{{ $comment->created_at->format('d.m.Y H:i') }}</small>
                            </div>
                            <div>
                                <button class="btn btn-sm btn-warning" onclick="editComment({{ $comment->id }})">✏️</button>
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить комментарий?')">🗑️</button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Форма редактирования -->
                        <form action="{{ route('comments.update', $comment) }}" method="POST" id="edit-comment-{{ $comment->id }}" class="mt-2 d-none">
                            @csrf
                            @method('PUT')
                            <textarea name="content" class="form-control mb-2" rows="2">{{ $comment->content }}</textarea>
                            <button type="submit" class="btn btn-sm btn-success">Сохранить</button>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="cancelEdit({{ $comment->id }})">Отмена</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function editComment(id) {
    document.getElementById('edit-comment-' + id).classList.remove('d-none');
}

function cancelEdit(id) {
    document.getElementById('edit-comment-' + id).classList.add('d-none');
}
</script>
@endpush
@endsection
