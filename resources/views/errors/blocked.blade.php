<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Доступ ограничен | Военный Журнал</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-danger shadow-lg">
                    <div class="card-header bg-danger text-white text-center py-3">
                        <h3 class="mb-0"><i class="bi bi-shield-lock-fill me-2"></i>Доступ заблокирован</h3>
                    </div>
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="bi bi-exclamation-octagon-fill text-danger" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="text-dark mb-3">Ваш аккаунт заблокирован</h4>
                        <p class="text-muted fs-5">
                            Администратор сайта заблокировал ваш доступ за нарушения правил платформы.
                        </p>
                        <hr class="my-4">
                        <p class="text-secondary small">Если вы считаете это ошибкой, обратитесь в техническую поддержку.</p>
                        
                        <form action="{{ route('logout') }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Выйти из системы
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>