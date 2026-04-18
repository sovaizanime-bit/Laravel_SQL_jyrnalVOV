@extends('layouts.app')

@section('title', 'Управление пользователями')

@section('content')
<div class="card">
    <div class="card-header bg-danger text-white">
        <h2 class="mb-0">Пользователи</h2>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Баллы</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $user->point->balance ?? 0 }} баллов
                                </span>
                            </td>
                            <td>
                                @if($user->is_blocked)
                                    <span class="badge bg-danger">Заблокирован</span>
                                @else
                                    <span class="badge bg-success">Активен</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    
                                    {{-- Начисление баллов (➕) --}}
                                    <form action="{{ route('admin.users.points', $user) }}" method="POST" class="d-flex align-items-center gap-1">
                                        @csrf
                                        <input type="number" name="points" min="1" value="10" style="width: 70px;" class="form-control form-control-sm">
                                        <button type="submit" class="btn btn-sm btn-success" title="Добавить баллы">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </form>
                                    
                                    {{-- Списание баллов (➖) --}}
                                    <form action="{{ route('admin.users.points.remove', $user) }}" method="POST" class="d-flex align-items-center gap-1">
                                        @csrf
                                        <input type="number" name="points" min="1" value="10" style="width: 70px;" class="form-control form-control-sm">
                                        <button type="submit" class="btn btn-sm btn-warning" title="Списать баллы">
                                            <i class="bi bi-dash-lg"></i>
                                        </button>
                                    </form>

                                    {{-- Кнопка блокировки/разблокировки --}}
                                    @if(!$user->isAdmin()) {{-- Админа банить нельзя! --}}
                                        <form action="{{ route('admin.users.toggle-block', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $user->is_blocked ? 'btn-success' : 'btn-danger' }}"
                                                    onclick="return confirm('{{ $user->is_blocked ? 'Разблокировать пользователя?' : 'Заблокировать пользователя?' }}')">
                                                <i class="bi {{ $user->is_blocked ? 'bi-unlock-fill' : 'bi-lock-fill' }}"></i>
                                                {{ $user->is_blocked ? 'Разблокировать' : 'Заблокировать' }}
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Пользователей пока нет</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection