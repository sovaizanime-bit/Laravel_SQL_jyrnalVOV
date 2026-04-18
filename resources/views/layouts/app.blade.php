<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Великая Отечественная') - Журнал статей</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #8B0000 0%, #FF4500 50%, #FFD700 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 2px 20px rgba(139, 0, 0, 0.3);
            border-bottom: 3px solid #8B0000;
        }

        .navbar-brand {
            color: #8B0000 !important;
            font-weight: 700;
            font-size: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .navbar-brand span {
            color: #FF4500;
        }

        .nav-link {
            color: #8B0000 !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #FF4500 !important;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: linear-gradient(45deg, #8B0000, #FF4500);
            border: none;
            box-shadow: 0 4px 15px rgba(139, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: linear-gradient(45deg, #FF4500, #8B0000);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(139, 0, 0, 0.4);
        }

        .btn-outline-danger {
            color: #8B0000;
            border: 2px solid #8B0000;
            background: transparent;
            transition: all 0.3s ease;
        }

        .btn-outline-danger:hover {
            background: #8B0000;
            color: white;
            transform: translateY(-2px);
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(139, 0, 0, 0.3);
        }

        .card-header {
            background: linear-gradient(45deg, #8B0000, #FF4500);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 15px 20px;
            font-weight: 600;
        }

        .card-header h1, .card-header h2, .card-header h3 {
            color: white;
            margin: 0;
        }

        .card-body {
            padding: 25px;
        }

        .article-title {
            color: #8B0000;
            font-weight: 700;
            margin-bottom: 15px;
            border-bottom: 2px solid #FF4500;
            padding-bottom: 10px;
        }

        .article-meta {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .article-meta i {
            color: #FF4500;
            margin-right: 5px;
        }

        .article-content {
            color: #333;
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .comment {
            background: rgba(255, 255, 255, 0.8);
            border-left: 4px solid #FF4500;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 0 10px 10px 0;
            transition: all 0.3s ease;
        }

        .comment:hover {
            background: white;
            border-left-width: 6px;
        }

        .comment-meta {
            color: #8B0000;
            font-size: 0.85rem;
            margin-bottom: 10px;
        }

        .comment-content {
            color: #333;
            margin-bottom: 10px;
        }

        .form-control {
            border: 2px solid #FF4500;
            border-radius: 10px;
            padding: 12px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #8B0000;
            box-shadow: 0 0 0 0.2rem rgba(139, 0, 0, 0.25);
        }

        .alert-success {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            border: none;
            border-radius: 10px;
        }

        .alert-danger {
            background: linear-gradient(45deg, #8B0000, #FF4500);
            color: white;
            border: none;
            border-radius: 10px;
        }

        .pagination .page-link {
            color: #8B0000;
            border: 2px solid #FF4500;
            margin: 0 3px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background: linear-gradient(45deg, #8B0000, #FF4500);
            color: white;
            border-color: transparent;
        }

        .pagination .active .page-link {
            background: linear-gradient(45deg, #8B0000, #FF4500);
            color: white;
            border-color: transparent;
        }

        .badge {
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 500;
        }

        .badge-success {
            background: #28a745;
            color: white;
        }

        .badge-warning {
            background: #FFD700;
            color: #8B0000;
        }

        .badge-danger {
            background: #8B0000;
            color: white;
        }

        footer {
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 20px 0;
            margin-top: 50px;
        }

        .gradient-text {
            background: linear-gradient(45deg, #8B0000, #FF4500, #FFD700);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        .search-form {
            position: relative;
        }

        .search-form input {
            padding-right: 50px;
        }

        .search-form button {
            position: absolute;
            right: 5px;
            top: 5px;
            background: linear-gradient(45deg, #8B0000, #FF4500);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 15px;
        }
    </style>

    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <span>В</span>еликая <span>О</span>течественная
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Главная</a>
                    </li>
                </ul>

                <form class="d-flex search-form me-3" action="{{ route('articles.search') }}" method="GET">
                    <input class="form-control" type="search" name="q" placeholder="Поиск статей..." value="{{ request('q') }}">
                    <button type="submit"><i class="bi bi-search"></i></button>
                </form>

                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('cart.index') }}">
                                🛒 Корзина
                                @if(auth()->user()->cart()->where('is_paid', false)->count() > 0)
                                    <span class="badge bg-danger">{{ auth()->user()->cart()->where('is_paid', false)->count() }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                                @if(Auth::user()->point && Auth::user()->point->balance > 0)
                                    <span class="badge bg-warning">{{ Auth::user()->point->balance }} баллов</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Личный кабинет</a></li>
                                <!-- СТРОКА С НАСТРОЙКАМИ УДАЛЕНА -->
                                <li><a class="dropdown-item" href="{{ route('articles.create') }}">Написать статью</a></li>
                                @if(Auth::user()->isAdmin())
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="{{ route('admin.index') }}">Админ-панель</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Выйти</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Войти</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Регистрация</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="gradient-text">Великая Отечественная</h5>
                    <p>Электронный журнал статей о Великой Отечественной войне. Сохраняем историю вместе.</p>
                </div>                
            </div>
            <hr class="bg-light">
            <div class="text-center text-white-50">
                © {{ date('Y') }} Великая Отечественная.
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
