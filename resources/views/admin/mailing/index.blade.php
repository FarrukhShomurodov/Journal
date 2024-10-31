@extends('admin.layouts.app')

@section('title')
    <title>Journal - Рассылка</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light">Рассылка</span>
    </h6>

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

    @if(session()->has('success'))
        <div class="alert alert-solid-success alert-dismissible d-flex align-items-center" role="alert">
            <i class="bx bx-check-circle fs-4 me-2"></i>
            {{ session()->get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">Настройки рассылки</h5>
            <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#filtersCollapse">
                <i class="bx bx-filter-alt"></i> Фильтры
            </button>
        </div>

        <div id="filtersCollapse"
             class="collapse @if(request('category_id') || request('establishment_id') || request('country_id') || request('city_id') || request('clinic_id') || request('specialization_id') || request('diseaseType_id') || request('entertainment_id') || request('hotel_id') || request('promotion_id') || request('usefulInformation_id') || request('currency_id')) show @endif">
            <div class="card-body">
                <form method="GET" action="{{ route('mailing') }}">
                    <div class="row g-3">
                        @php
                            $filters = [
                                ['id' => 'category', 'label' => 'Категории', 'items' => $categories],
                                ['id' => 'establishment', 'label' => 'Заведения', 'items' => $establishments],
                                ['id' => 'country', 'label' => 'Страны', 'items' => $countries],
                                ['id' => 'city', 'label' => 'Города', 'items' => $cities],
                                ['id' => 'clinic', 'label' => 'Клиники', 'items' => $clinics],
                                ['id' => 'specialization', 'label' => 'Специализации', 'items' => $specializations],
                                ['id' => 'diseaseType', 'label' => 'Типы заболеваний', 'items' => $diseaseTypes],
                                ['id' => 'entertainment', 'label' => 'Развлечения', 'items' => $entertainments],
                                ['id' => 'hotel', 'label' => 'Отели', 'items' => $hotels],
                                ['id' => 'promotion', 'label' => 'Акции', 'items' => $promotions],
                                ['id' => 'usefulInformation', 'label' => 'Полезная информация', 'items' => $usefulInformations],
                                ['id' => 'currency', 'label' => 'Валюты', 'items' => $currencies]
                            ];
                        @endphp

                        @foreach($filters as $filter)
                            <div class="col-md-3 form-group">
                                <label for="{{ $filter['id'] }}">{{ $filter['label'] }}</label>
                                <select id="{{ $filter['id'] }}" name="{{ $filter['id'] }}_id"
                                        class="form-control select2">
                                    <option value="">Все</option>
                                    @foreach($filter['items'] as $item)
                                        <option value="{{ $item->id }}"
                                            {{ request("{$filter['id']}_id") == $item->id ? 'selected' : '' }}>
                                            {{ $filter['id'] == 'currency' ? json_decode($item->name)->ru : $item->name['ru'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Применить фильтр</button>
                </form>
            </div>
        </div>

        <div class="card-body">
            <form class="d-flex flex-column gap-3" method="POST" action="{{ route('bot.sendMessage') }}">
                @csrf
                <div class="form-group">
                    <label for="text" class="form-label">Сообщение</label>
                    <textarea name="text" id="text" rows="4" class="form-control @error('text') is-invalid @enderror"
                              placeholder="Введите сообщение..." required></textarea>
                    @foreach ($botUsers as $botUser)
                        <input type="hidden" name="chat_ids[]" value="{{ $botUser->chat_id }}">
                    @endforeach
                    @error('text')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Отправить сообщение</button>
            </form>
        </div>
    </div>

{{--    <div class="card mt-4">--}}
{{--        <div class="card-datatable table-responsive">--}}
{{--            <table class="datatables-users table border-top">--}}
{{--                <thead>--}}
{{--                <tr>--}}
{{--                    <th>id</th>--}}
{{--                    <th>Chat id</th>--}}
{{--                    <th>Имя</th>--}}
{{--                    <th>Фамилия</th>--}}
{{--                    <th>Имя пользователя</th>--}}
{{--                    <th>Телефон</th>--}}
{{--                    <th>Шаг</th>--}}
{{--                    <th>Активный</th>--}}
{{--                    <th>Местоположение</th>--}}
{{--                    <th>Первое посещение</th>--}}
{{--                    <th>Последнее посещение</th>--}}
{{--                    <th>Путь</th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}
{{--                @php $userCount = 1; @endphp--}}
{{--                @foreach($botUsers as $user)--}}
{{--                    <tr>--}}
{{--                        <td>{{ $userCount++ }}</td>--}}
{{--                        <td>{{ $user->chat_id }}</td>--}}
{{--                        <td>{{ $user->first_name }}</td>--}}
{{--                        <td>{{ $user->second_name }}</td>--}}
{{--                        <td>{{ $user->uname }}</td>--}}
{{--                        <td>{{ $user->phone }}</td>--}}
{{--                        <td>{{ $user->step }}</td>--}}
{{--                        <td>--}}
{{--                            <label class="switch">--}}
{{--                                <input type="checkbox" class="switch-input" data-user-id="{{ $user->id }}"--}}
{{--                                       @if($user->isactive) checked @endif>--}}
{{--                                <span class="switch-toggle-slider">--}}
{{--                                    <span class="switch-on"></span>--}}
{{--                                    <span class="switch-off"></span>--}}
{{--                                </span>--}}
{{--                            </label>--}}
{{--                        </td>--}}
{{--                        <td>{{ $user->country ? $user->country->name['ru'] . ',' : '' }} {{ $user->city ? $user->city->name['ru'] : '' }}</td>--}}
{{--                        <td>{{ $user->created_at }}</td>--}}
{{--                        <td>{{ $user->last_activity }}</td>--}}
{{--                        <td>--}}
{{--                            <button class="btn btn-sm btn-icon"--}}
{{--                                    onclick="location.href='{{ route('bot.user.journey', $user->id) }}'">--}}
{{--                                <i class="bx bx-show"></i>--}}
{{--                            </button>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
{{--                </tbody>--}}
{{--            </table>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection
