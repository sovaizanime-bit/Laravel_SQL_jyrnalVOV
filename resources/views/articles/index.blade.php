@extends('layouts.app')

@section('title', 'Главная')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">
                    <i class="bi bi-newspaper me-2"></i>
                    @if(isset($query))
                        Результаты поиска: "{{ $query }}"
                    @else
                        Последние статьи
                    @endif
                </h2>
            </div>
            <div class="card-body">
                @if($articles->count() > 0)
                    <div class="row">
                        @foreach($articles as $article)
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    @if($article->image)
                                        <img src="{{ asset('storage/' . $article->image) }}" 
                                             class="card-img-top" 
                                             alt="{{ $article->title }}"
                                             style="height: 200px; object-fit: cover;">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="article-title">
                                            {{ $article->title }}
                                            @if($article->is_blocked)
                                                <span class="badge bg-info text-dark ms-2">Заблокировано</span>
                                            @endif
                                        </h5>
                                        <div class="article-meta">
                                            <i class="bi bi-person"></i> {{ $article->user->name }} |
                                            <i class="bi bi-calendar"></i> {{ $article->created_at->format('d.m.Y') }} |
                                            <i class="bi bi-chat"></i> {{ $article->comments->count() }} |
                                            <span class="badge bg-warning">{{ $article->price > 0 ? $article->price . ' баллов' : 'Бесплатно' }}</span>
                                        </div>
                                        <p class="article-content">
                                            {{ Str::limit(strip_tags($article->content), 150) }}
                                        </p>
                                        <a href="{{ route('articles.show', $article) }}" 
                                           class="btn btn-danger w-100">
                                            Читать далее
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $articles->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <h3 class="gradient-text">Статей пока нет</h3>
                        <p class="text-muted">Будьте первым, кто опубликует статью!</p>
                        @auth
                            <a href="{{ route('articles.create') }}" class="btn btn-danger">
                                Написать статью
                            </a>
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection