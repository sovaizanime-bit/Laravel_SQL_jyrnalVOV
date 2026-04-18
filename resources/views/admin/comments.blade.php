@extends('layouts.app')

@section('title', 'Управление комментариями')

@section('content')
<div class="card">
    <div class="card-header bg-danger text-white">
        <h2 class="mb-0">Комментарии</h2>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Комментарий</th>
                    <th>Автор</th>
                    <th>Статья</th>
                    <th>Дата</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($comments as $comment)
                @php
                    $isBanned = App\Models\Ban::where('bannable_type', 'App\Models\Comment')
                        ->where('bannable_id', $comment->id)
                        ->exists();
                @endphp
                <tr>
                    <td>{{ $comment->id }}</td>
                    <td>
                        <div class="comment-content" style="max-width: 300px; max-height: 100px; overflow-y: auto;">
                            {{ $comment->content }}
                        </div>
                    </td>
                    <td>{{ $comment->user->name }}</td>
                    <td>
                        <a href="{{ route('articles.show', $comment->article) }}" target="_blank">
                            {{ Str::limit($comment->article->title, 30) }}
                        </a>
                    </td>
                    <td>{{ $comment->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        @if($isBanned)
                            <span class="badge bg-danger">Заблокирован</span>
                        @else
                            <span class="badge bg-success">Активен</span>
                        @endif
                    </td>
                    <td>
                        @if($isBanned)
                            <!-- РАЗБЛОКИРОВАТЬ -->
                            <form action="{{ route('admin.comments.unban', $comment) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Разблокировать</button>
                            </form>
                        @else
                            <!-- ЗАБЛОКИРОВАТЬ (любой комментарий - свой или чужой) -->
                            <form action="{{ route('admin.comments.ban', $comment) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Заблокировать комментарий?')">Заблокировать</button>
                            </form>
                        @endif
                        
                        <!-- ПОСМОТРЕТЬ ПОЛНОСТЬЮ (развернуть) -->
                        <button type="button" class="btn btn-sm btn-info" onclick="showFullComment({{ $comment->id }}, '{{ addslashes($comment->content) }}')">🔍</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $comments->links() }}
    </div>
</div>

<!-- Модальное окно для полного комментария -->
<div class="modal fade" id="commentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Полный комментарий</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="fullCommentText">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showFullComment(id, content) {
    document.getElementById('fullCommentText').innerText = content;
    var modal = new bootstrap.Modal(document.getElementById('commentModal'));
    modal.show();
}
</script>
@endpush
@endsection
