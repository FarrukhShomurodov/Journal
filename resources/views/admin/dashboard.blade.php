@extends('admin.layouts.app')

@section('title')
    <title>Dashboard - Analytics | Journal</title>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-solid-danger alert-dismissible d-flex align-items-center" role="alert">
            <i class="bx bx-error-circle fs-4 me-2"></i>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container-fluid">
        <div class="row mb-4 align-items-center justify-content-between">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold text-primary">Аналитика</h1>
                <p class="lead text-muted">Добро пожаловать на панель управления. Следите за активностью пользователей и
                    основными метриками вашего бота.</p>
            </div>
            <div class="col-md-4">
                <div class="col-12 text-end mb-4">
                    <a href="{{ route('dashboard.statistics.export', ['date_from_rr' => $dateFromRR, 'date_to_rr' => $dateToRR, 'date_from_session_frequency' => $dateFromFrequency, 'date_to_session_frequency' => $dateToFrequency, 'date_from_session_churn' => $dateFromChurn, 'date_to_session_churn' => $dateToChurn]) }}"
                       class="btn btn-success btn-sm">
                        <i class="fas fa-file-export me-2"></i>Экспорт статистики
                    </a>
                </div>
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body">
                        <i class="fas fa-users text-primary mb-3" style="font-size: 2rem;"></i>
                        <h4 class="card-title mb-1">{{ $statistics['user_count'] }}</h4>
                        <p class="text-muted">Пользователи бота</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Статистика -->
        <div class="row">
            <!-- Карточки статистики -->
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body">
                        <i class="fas fa-user-clock fa-3x text-primary mb-3"></i>
                        <h4 class="card-title mb-1">{{ $statistics['dau'] }}</h4>
                        <p class="text-muted">Ежедневные уникальные пользователи</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x text-success mb-3"></i>
                        <h4 class="card-title mb-1">{{ $statistics['wau'] }}</h4>
                        <p class="text-muted">Еженедельные уникальные пользователи</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body">
                        <i class="fas fa-users fa-3x text-warning mb-3"></i>
                        <h4 class="card-title mb-1">{{ $statistics['mau'] }}</h4>
                        <p class="text-muted">Ежемесячные уникальные пользователи</p>
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
                        <h4 class="card-title mb-1">{{ $statistics['retention_rate'] }}</h4>
                        <p class="text-muted">Удержание пользователей</p>
                        <form method="GET" action="" class="mt-3">
                            <div class="d-flex justify-content-center">
                                <input onchange="this.form.submit()" name="date_from_rr" type="date"
                                       class="form-control me-2" value="{{ $dateFromRR }}">
                                <input onchange="this.form.submit()" name="date_to_rr" type="date" class="form-control"
                                       value="{{ $dateToRR }}">
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
                        <h4 class="card-title mb-1">{{ $statistics['get_average_session_frequency'] }}</h4>
                        <p class="text-muted">Средняя частота сессий</p>
                        <form method="GET" action="" class="mt-3">
                            <div class="d-flex justify-content-center">
                                <input onchange="this.form.submit()" name="date_from_session_frequency" type="date"
                                       class="form-control me-2" value="{{ $dateFromFrequency }}">
                                <input onchange="this.form.submit()" name="date_to_session_frequency" type="date"
                                       class="form-control" value="{{ $dateToFrequency }}">
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
                        <h4 class="card-title mb-1">{{ $statistics['calculate_churn_rate'] }}</h4>
                        <p class="text-muted">Уровень оттока</p>
                        <form method="GET" action="" class="mt-3">
                            <div class="d-flex justify-content-center">
                                <input onchange="this.form.submit()" name="date_from_session_churn" type="date"
                                       class="form-control me-2" value="{{ $dateFromChurn }}">
                                <input onchange="this.form.submit()" name="date_to_session_churn" type="date"
                                       class="form-control" value="{{ $dateToChurn }}">
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
                        <h4 class="card-title mb-1">{{ $statistics['drop_off_point'] }}</h4>
                        <p class="text-muted">Ключевая точка оттока</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body">
                        <h4 class="card-title mb-1">{{ $statistics['user_counts_by_menu_section'] }}</h4>
                        <p class="text-muted">Пользователи по разделам меню</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body">
                        <h4 class="card-title mb-1">{{ $statistics['average_session_length'] }}</h4>
                        <p class="text-muted">Средняя длительность сессии</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body">
                        <h4 class="card-title mb-1">{{ $statistics['most_frequent_country'] }}</h4>
                        <p class="text-muted">Самая часто выбираемая страна</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body">
                        <h4 class="card-title mb-1">{{ $statistics['most_frequent_city'] }}</h4>
                        <p class="text-muted">Самый часто выбираемый город</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <h2>Процент пользователей, которые прошли весь путь от начала до конца (по пунктам)</h2>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body">
                        <h4 class="card-title mb-1">{{ $statistics['user_journey_completion_rate']['application'] }}
                            %</h4>
                        <p class="text-muted">Завершение подачи заявки</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body">
                        <h4 class="card-title mb-1">{{ $statistics['user_journey_completion_rate']['promotionViewCount'] }}
                            %</h4>
                        <p class="text-muted">Просмотр акций</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-1">{{ $statistics['user_journey_completion_rate']['useFullInformationViewCount'] }}
                            %</h4>
                        <p class="text-muted">Просмотр полезной информации</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-1">{{ $statistics['user_journey_completion_rate']['hotelViewCount'] }}
                            %</h4>
                        <p class="mb-0 text-muted">Просмотр отелей</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-1">{{ $statistics['user_journey_completion_rate']['establishmentViewCount'] }}
                            %</h4>
                        <p class="mb-0 text-muted">Просмотр заведений</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-1">{{ $statistics['user_journey_completion_rate']['currencyViewCount'] }}
                            %</h4>
                        <p class="mb-0 text-muted">Просмотр валютных данных</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card stat-card shadow-sm border-0 text-center hover-scale">
                    <div class="card-body p-4">
                        <h4 class="card-title mb-1">{{ $statistics['user_journey_completion_rate']['entertainmentViewCount'] }}
                            %</h4>
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
