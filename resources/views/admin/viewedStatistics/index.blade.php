@extends('admin.layouts.app')

@section('title', 'Journal | Кол-во просмотров')

@section('content')
    <style>
        .card {
            margin-bottom: 1rem;
        }

        .card-header {
            font-size: 1rem;
            padding: 0.75rem;
        }

        .pagination {
            justify-content: center;
            margin: 0.5rem 0;
        }

        .table {
            font-size: 0.875rem;
            margin-bottom: 0;
        }

        .table th, .table td {
            padding: 0.5rem;
        }

        .no-data {
            text-align: center;
            font-style: italic;
        }
    </style>

    <h6 class="py-3 breadcrumb-wrapper mb-3">
        <span class="text-muted fw-light"><a class="text-muted" href="{{ route('dashboard') }}">Журнал /</a></span>Кол-во
        просмотров
    </h6>

    <div class="col-12 text-end mb-3">
        <a href="{{ route('viewed.statistics.export') }}" class="btn btn-success btn-sm">
            <i class="fas fa-file-export me-2"></i>Экспорт статистики
        </a>
    </div>

    @php
        $sections = [
            'Страны' => ['data' => $countries, 'icon' => 'bx-category', 'name' => 'country'],
            'Города' => ['data' => $cities, 'icon' => 'tf-icons bx-health', 'name' => 'city'],
            'Клиники' => ['data' => $clinics, 'icon' => 'tf-icons bx-health', 'name' => 'clinic'],
            'Типы заболеваний' => ['data' => $diseaseTypes, 'icon' => 'bx-bug', 'name' => 'diseaseType'],
            'Специализации' => ['data' => $specializations, 'icon' => 'bx-briefcase', 'name' => 'specialization'],
            'Валюты' => ['data' => $currencies, 'icon' => 'bx bx-money', 'name' => 'currency'],
            'Категории' => ['data' => $categories, 'icon' => 'bx-category', 'name' => 'category'],
            'Заведения' => ['data' => $establishments, 'icon' => 'bx-store', 'name' => 'establishment'],
            'Развлечения' => ['data' => $entertainments, 'icon' => 'bx-party', 'name' => 'entertainment'],
            'Отели' => ['data' => $hotels, 'icon' => 'bx-building', 'name' => 'hotel'],
            'Полезная информация' => ['data' => $usefulInformations, 'icon' => 'bx-info-circle', 'name' => 'usefulInformation'],
            'Акции' => ['data' => $promotions, 'icon' => 'bx-gift', 'name' => 'promotion'],
        ];
    @endphp

    <div class="row">
        @foreach($sections as $title => $section)
            <div class="col-md-6">
                <div class="card">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-header">{{ $title }}</h6>
                        <i class="bx {{ $section['icon'] }} icon me-2"></i>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Кол-во</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($section['data'] as $item)
                                    <tr>
                                        <td>{{ $section['name'] == 'currency' ? json_decode($item->name)->ru : $item->name['ru'] }}</td>
                                        <td>{{ in_array($section['name'], ['city', 'country']) ? $item->users_count : $item->views_count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="no-data">Нет данных для отображения</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            {{ $section['data']->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
            @if (($loop->index + 1) % 2 == 0)
    </div>
    <div class="row">
        @endif
        @endforeach
    </div>
@endsection
