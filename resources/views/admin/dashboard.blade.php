@extends('admin.layouts.app')

@section('title')
    <title>Dashboard - Analytics | Journal - Bootstrap Admin</title>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                <h1 class="display-4">Аналитика</h1>
                <p class="lead">Добро пожаловать на панель управления, следите за активностью пользователей и
                    метриками.</p>
            </div>
        </div>

        <!-- Статистика -->
        <div class="row">
            <!-- Карточки статистики -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body">
                        <i class="fas fa-user-clock fa-3x text-primary mb-3"></i>
                        <h4 class="card-title mb-1">{{ $statistics['active_users']['dau'] }}</h4>
                        <p class="text-muted">Ежедневные активные пользователи</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x text-success mb-3"></i>
                        <h4 class="card-title mb-1">{{ $statistics['active_users']['wau'] }}</h4>
                        <p class="text-muted">Еженедельные активные пользователи</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x text-warning mb-3"></i>
                        <h4 class="card-title mb-1">{{ $statistics['active_users']['mau'] }}</h4>
                        <p class="text-muted">Ежемесячные активные пользователи</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Удержание пользователей -->
        <div class="row mb-4">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-3x text-info mb-3"></i>
                        <h4 class="card-title mb-1">{{ round($statistics['retention_rate'], 2) }}%</h4>
                        <p class="text-muted">Удержание пользователей</p>
                        <form method="GET" action="" class="mt-3">
                            <div class="d-flex justify-content-center">
                                <input onchange="this.form.submit()" name="date_from" type="date"
                                       class="form-control me-2" value="{{ request('date_from') }}">
                                <input onchange="this.form.submit()" name="date_to" type="date" class="form-control"
                                       value="{{ request('date_to') }}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Средняя частота сессий -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body">
                        <i class="fas fa-clock fa-3x text-danger mb-3"></i>
                        <h4 class="card-title mb-1">{{ round($statistics['get_average_session_frequency'], 2) }}</h4>
                        <p class="text-muted">Средняя частота сессий</p>
                        <form method="GET" action="" class="mt-3">
                            <div class="d-flex justify-content-center">
                                <input onchange="this.form.submit()" name="date_from_session_frequency" type="date"
                                       class="form-control me-2" value="{{ request('date_from_session_frequency') }}">
                                <input onchange="this.form.submit()" name="date_to_session_frequency" type="date"
                                       class="form-control" value="{{ request('date_to_session_frequency') }}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Уровень оттока -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body">
                        <i class="fas fa-arrow-alt-circle-down fa-3x text-dark mb-3"></i>
                        <h4 class="card-title mb-1">{{ round($statistics['calculate_churn_rate'], 2) }}%</h4>
                        <p class="text-muted">Уровень оттока</p>
                        <form method="GET" action="" class="mt-3">
                            <div class="d-flex justify-content-center">
                                <input onchange="this.form.submit()" name="date_from_session_churn" type="date"
                                       class="form-control me-2" value="{{ request('date_from_session_churn') }}">
                                <input onchange="this.form.submit()" name="date_to_session_churn" type="date"
                                       class="form-control" value="{{ request('date_to_session_churn') }}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Графики активности -->
        <h2>Активность пользователей во времени: анализ роста и взаимодействия</h2>
        <canvas id="activeUsersChart"></canvas>

        <!-- Дополнительная статистика -->
        <div class="row mt-5">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body">
                        <h4 class="card-title mb-1">{{ $statistics['active_users']['drop_off_point'] }}</h4>
                        <p class="text-muted">Ключевая точка оттока</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body">
                        <h4 class="card-title mb-1">{{ $statistics['active_users']['user_counts_by_menu_section'] }}</h4>
                        <p class="text-muted">Пользователи по разделам меню</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body">
                        <h4 class="card-title mb-1">{{ $statistics['active_users']['average_session_length'] ? gmdate("H:i:s", $statistics['active_users']['average_session_length']) : '-' }}</h4>
                        <p class="text-muted">Средняя длительность сессии</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <h2>Процент пользователей, которые прошли весь путь от начала до конца (по пунктам)</h2>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body">
                        <h4 class="card-title mb-1">{{ $statistics['active_users']['user_journey_completion_rate']['application'] }}%</h4>
                        <p class="text-muted">Завершение подачи заявки</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                <div class="card-body">
                        <h4 class="card-title mb-1">{{ $statistics['active_users']['user_journey_completion_rate']['promotionViewCount'] }}%</h4>
                        <p class="text-muted">Просмотр акций</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                <div class="card-body p-4">
                        <h4 class="card-title mb-1">{{ $statistics['active_users']['user_journey_completion_rate']['useFullInformationViewCount'] }}%</h4>
                        <p class="text-muted">Просмотр полезной информации</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                <div class="card-body p-4">
                        <h4 class="card-title mb-1">{{ $statistics['active_users']['user_journey_completion_rate']['hotelViewCount'] }}%</h4>
                        <p class="mb-0 text-muted">Просмотр отелей</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                <div class="card-body p-4">
                        <h4 class="card-title mb-1">{{ $statistics['active_users']['user_journey_completion_rate']['establishmentViewCount'] }}%</h4>
                        <p class="mb-0 text-muted">Просмотр заведений</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                <div class="card-body p-4">
                        <h4 class="card-title mb-1">{{ $statistics['active_users']['user_journey_completion_rate']['currencyViewCount'] }}%</h4>
                        <p class="mb-0 text-muted">Просмотр валютных данных</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                <div class="card-body p-4">
                        <h4 class="card-title mb-1">{{ $statistics['active_users']['user_journey_completion_rate']['entertainmentViewCount'] }}%</h4>
                        <p class="mb-0 text-muted">Просмотр развлечений</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const myChart = new Chart("activeUsersChart", {
            type: "line",
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Active Users',
                    data: @json($chartData),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                }]
            },
            options: {
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day',
                            tooltipFormat: 'yyyy-MM-dd',
                        },
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Active Users'
                        }
                    }
                }
            }
        });
    </script>
@endsection
