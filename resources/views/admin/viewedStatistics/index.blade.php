@extends('admin.layouts.app')

@section('title')
    <title>Journal | Кол-во просмотров</title>
@endsection

@section('content')
    <style>
        .card {
            margin-bottom: 1.5rem;
        }

        .pagination {
            justify-content: center;
            margin: 1rem 0;
        }
    </style>

    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light"><a class="text-muted"
                                             href="{{ route('dashboard') }}">Журнал /</a></span>Кол-во просмотров
    </h6>

    <div class="col-12 text-end mb-4">
        <a href="{{ route('viewed.statistics.export') }}"
           class="btn btn-success btn-sm">
            <i class="fas fa-file-export me-2"></i>Экспорт статистики
        </a>
    </div>

    @php
        $sections = [
            'Страны' => ['data' => $countries, 'icon' => 'bx-category', 'name' => 'country'],
            'Города' => ['data' => $cities, 'icon' => 'tf-icons bx-health', 'name' => 'city'],
            'Валюты' => ['data' => $currencies, 'icon' => ' tf-icons bx bx-money', 'name' => 'currency'],
            'Категории' => ['data' => $categories, 'icon' => 'bx-category', 'name' => 'category'],
            'Заведения' => ['data' => $establishments, 'icon' => 'bx-store', 'name' => 'establishment'],
            'Развлечения' => ['data' => $entertainments, 'icon' => 'bx-party', 'name' => 'entertainment'],
            'Отели' => ['data' => $hotels, 'icon' => 'bx-building', 'name' => 'hotel'],
            'Полезная информация' => ['data' => $usefulInformations, 'icon' => 'bx-info-circle', 'name' => 'usefulInformation'],
            'Акции' => ['data' => $promotions, 'icon' => 'bx-gift', 'name' => 'promotion'],
            'Клиники' => ['data' => $clinics, 'icon' => 'tf-icons bx-health', 'name' => 'clinic'],
            'Типы заболеваний' => ['data' => $diseaseTypes, 'icon' => 'bx-bug', 'name' => 'diseaseType'],
            'Специализации' => ['data' => $specializations, 'icon' => 'bx-briefcase', 'name' => 'specialization'],
        ];
    @endphp

    @foreach($sections as $title => $section)
        <div class="card">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-header">{{ $title }}</h5>
                <i class="bx {{ $section['icon'] }} icon me-2"></i>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover">
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
                                <td>{{ $item->views_count }}</td>
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
    @endforeach
@endsection
