@extends('layouts.app')

@section('title', 'Статистика')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">Статистика активности</h2>
            </div>
            <div class="card-body">
                <canvas id="statsChart" style="width:100%; max-height:400px"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('statsChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dates) !!},
            datasets: [{
                label: 'Новые статьи',
                data: {!! json_encode($articlesCount) !!},
                borderColor: '#8B0000',
                backgroundColor: 'rgba(139, 0, 0, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Новые пользователи',
                data: {!! json_encode($usersCount) !!},
                borderColor: '#FF4500',
                backgroundColor: 'rgba(255, 69, 0, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Активность за последние 7 дней'
                },
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection