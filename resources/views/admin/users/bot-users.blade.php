@extends('admin.layouts.app')

@section('title')
    <title>Journal - Поользователи бота</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light">Пользователи бота</span>
    </h6>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Пользователи бота</h5>
            <form method="GET" action="">
                <div class="d-flex">
                    <div class="d-flex flex-row align-items-center " style="margin-right: 10px">
                        <label class="me-2">От: </label>
                        <input onchange="this.form.submit()" name="date_from" type="date" class="form-control"
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="d-flex flex-row align-items-center " style="margin-right: 10px">
                        <label class="me-2">До: </label>
                        <input onchange="this.form.submit()" name="date_to" type="date" class="form-control"
                               value="{{ request('date_to') }}">
                    </div>
                </div>
            </form>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                <tr>
                    <th>id</th>
                    <th>Chat id</th>
                    <th>Имя</th>
                    <th>Фамилия</th>
                    <th>Имя пользоватля</th>
                    <th>Телефон</th>
                    <th>Шаг</th>
                    <th>Активный</th>
                    <th>Местоположения</th>
                    <th>Первое посешение</th>
                    <th>Последное посешение</th>
                    <th>Путь</th>
                </tr>
                </thead>
                <tbody>

                @php
                    $userCount = 1
                @endphp

                @foreach($botUsers as $user)
                    <tr>
                        <td>{{ $userCount++ }}</td>
                        <td>{{ $user->chat_id }}</td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->second_name }}</td>
                        <td>{{ $user->uname }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->step}}</td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-user-id="{{ $user->id }}"
                                       @if($user->isactive) checked @endif>
                                <span class="switch-toggle-slider">
                                    <span class="switch-on"></span>
                                    <span class="switch-off"></span>
                                </span>
                            </label>
                        </td>
                        <td>{{ $user->country->name['ru'].','.$user->city->name['ru'] }}</td>
                        <td>{{ $user->created_at}}</td>
                        <td>{{ $user->last_activity}}</td>
                        <td>
                            <button class="btn btn-sm btn-icon"
                                    onclick="location.href='{{ route('bot.user.journey', $user->id) }}'">
                                <i class="bx bx-show"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('.switch-input').on('change', function () {
                let userId = $(this).data('user-id');
                let isActive = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                    url: `/api/bot-users/${userId}/is-active`,
                    method: 'put',
                    data: {
                        _token: '{{ csrf_token() }}',
                        isactive: isActive
                    },
                    success: function (res) {
                    },
                    error: function (error) {
                    }
                });
            });
        });
    </script>
@endsection
