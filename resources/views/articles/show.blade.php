@extends('layouts.app')

@section('title', $article->title)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h1 class="mb-0">
                    {{ $article->title }}
                    @if($article->is_blocked)
                        <span class="badge bg-info text-dark ms-2" style="font-size: 0.5em;">Заблокировано</span>
                    @endif
                </h1>

                {{-- Кнопка блокировки для администратора --}}
                @if(auth()->check() && auth()->user()->isAdmin())
                    <div class="mt-2">
                        <form action="{{ route('articles.toggle-block', $article) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $article->is_blocked ? 'btn-success' : 'btn-danger' }}">
                                <i class="bi bi-slash-circle"></i> 
                                {{ $article->is_blocked ? 'Разблокировать' : 'Заблокировать' }}
                            </button>
                        </form>
                    </div>
                @endif
            </div>
            <div class="card-body">
                <div class="article-meta">
                    <i class="bi bi-person"></i> {{ $article->user->name }} |
                    <i class="bi bi-calendar"></i> {{ $article->created_at->format('d.m.Y H:i') }} |
                    <i class="bi bi-chat"></i> {{ $comments->count() }} комментариев |
                    <span class="badge bg-warning">{{ $article->price > 0 ? $article->price . ' баллов' : 'Бесплатно' }}</span>
                </div>

                @if($article->image)
                    <div class="mt-4 text-center">
                        <img src="{{ asset('storage/' . $article->image) }}" class="img-fluid rounded" alt="{{ $article->title }}">
                    </div>
                @endif

                <div class="article-content mt-4">
                    @php
                        // Проверяем условия полного доступа:
                        // 1. Статья бесплатная (price == 0)
                        // 2. ИЛИ она куплена ($isPaid)
                        // 3. ИЛИ текущий пользователь — её автор
                        // 4. ИЛИ текущий пользователь — админ
                        $hasAccess = ($article->price == 0) || 
                                    $isPaid || 
                                    (auth()->check() && (auth()->id() == $article->user_id || auth()->user()->isAdmin()));
                    @endphp

                    @if($hasAccess)
                        {!! nl2br(e($article->content)) !!}
                    @else
                        <div class="text-muted">
                            {!! nl2br(e(Str::words($article->content, 13, '...'))) !!}
                        </div>
                        
                        <div class="alert alert-danger mt-3">
                            <i class="bi bi-lock-fill"></i> Полный текст статьи доступен только после покупки.
                        </div>
                    @endif
                </div>

                @auth
                    @if(auth()->id() == $article->user_id)
                        <div class="mt-4">
                            <a href="{{ route('articles.edit', $article) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Редактировать
                            </a>
                            <form action="{{ route('articles.destroy', $article) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Вы уверены?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Удалить
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth

                {{-- ИСПРАВЛЕНО: $canRead → $isPaid --}}
                @if($article->price > 0 && !$isPaid && auth()->check() && auth()->id() != $article->user_id)
                    <div class="alert alert-warning mt-4">
                        <h5>Доступ ограничен</h5>
                        <p>Для чтения этой статьи необходимо оплатить {{ $article->price }} баллов.</p>
                        <form action="{{ route('cart.add', $article) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                Добавить в корзину
                            </button>
                        </form>
                    </div>
                @endif

                {{-- Показываем комментарии если: статья бесплатная ИЛИ куплена ИЛИ автор ИЛИ админ --}}
                @if($article->price == 0 || $isPaid || (auth()->check() && (auth()->id() == $article->user_id || auth()->user()->isAdmin())))
                    <!-- Комментарии -->
                    <div class="mt-5">
                        <h3 class="article-title">Комментарии ({{ $comments->count() }})</h3>

                        @auth
                            <form action="{{ route('comments.store', $article) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="form-group mb-3">
                                    <textarea name="content" 
                                              class="form-control @error('content') is-invalid @enderror" 
                                              rows="3" 
                                              placeholder="Напишите комментарий..."></textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-danger">
                                    Отправить
                                </button>
                            </form>
                        @else
                            <p class="text-muted">Чтобы оставить комментарий, <a href="{{ route('login') }}">войдите в профиль</a>!</p>
                        @endauth

                        @forelse($comments as $comment)
                            <div class="comment" id="comment-{{ $comment->id }}">
                                <div class="comment-meta">
                                    <strong>{{ $comment->user->name }}</strong> |
                                    {{ $comment->created_at->diffForHumans() }}
                                </div>
                                <div class="comment-content">
                                    {{ $comment->content }}
                                </div>
                                @auth
                                    @if(auth()->id() == $comment->user_id)
                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-warning" 
                                                    onclick="editComment({{ $comment->id }})">
                                                Редактировать
                                            </button>
                                            <form action="{{ route('comments.destroy', $comment) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Удалить комментарий?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    Удалить
                                                </button>
                                            </form>
                                        </div>

                                        <form action="{{ route('comments.update', $comment) }}" 
                                              method="POST" 
                                              class="mt-3 d-none" 
                                              id="edit-form-{{ $comment->id }}">
                                            @csrf
                                            @method('PUT')
                                            <textarea name="content" class="form-control mb-2" rows="2">{{ $comment->content }}</textarea>
                                            <button type="submit" class="btn btn-sm btn-success">Сохранить</button>
                                            <button type="button" class="btn btn-sm btn-secondary" onclick="cancelEdit({{ $comment->id }})">Отмена</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        @empty
                            <p class="text-muted">Пока нет комментариев</p>
                        @endforelse
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Информация</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-person"></i> Автор: {{ $article->user->name }}
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-calendar"></i> Дата: {{ $article->created_at->format('d.m.Y') }}
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-chat"></i> Комментариев: {{ $comments->count() }}
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-coin"></i> Стоимость: {{ $article->price > 0 ? $article->price . ' баллов' : 'Бесплатно' }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editComment(id) {
    document.getElementById('edit-form-' + id).classList.remove('d-none');
}

function cancelEdit(id) {
    document.getElementById('edit-form-' + id).classList.add('d-none');
}
</script>
@endpush
@endsection
