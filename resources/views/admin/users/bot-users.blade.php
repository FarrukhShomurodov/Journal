@extends('admin.layouts.app')

@section('title')
    <title>Journal - Пользователи бота</title>
@endsection

<?php
$firstDayOfMonth = date('Y-m-01');
$today = date('Y-m-d');
?>

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light">Пользователи бота</span>
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


    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Пользователи бота</h5>
            <div class="d-flex">
                <div class="d-flex flex-row align-items-center me-2">
                    <label for="from" class="me-2">От:</label>
                    <input id="from" name="date_from" type="date"
                           class="form-control form-control-sm" value="{{$firstDayOfMonth}}">
                </div>
                <div class="d-flex flex-row align-items-center me-2">
                    <label for="to" class="me-2">До:</label>
                    <input id="to" name="date_to" type="date"
                           class="form-control form-control-sm" value="{{$today}}">
                </div>
                <div class="me-2">
                    <input id="phone" name="phone" type="text" class="form-control form-control-sm"
                           placeholder="Телефон">
                </div>
                <div class="d-flex flex-row align-items-center me-2">
                    <button class="btn btn-success btn-sm" id="export-statistics"><i
                            class="fas fa-file-export me-2"></i>Экспорт статистики
                    </button>
                </div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                <tr>
                    <th>id</th>
                    <th>Chat id</th>
                    <th>Имя</th>
                    <th>Фамилия</th>
                    <th>Имя пользователя</th>
                    <th>Телефон</th>
                    <th>Шаг</th>
                    <th>Активный</th>
                    <th>Местоположение</th>
                    <th>Первое посещение</th>
                    <th>Последнее посещение</th>
                    <th>Путь</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            function fetchUsers() {
                const dateFrom = $('#from').val();
                const dateTo = $('#to').val();
                const phone = $('#phone').val();

                $.ajax({
                    url: `/api/bot-users?date_from=${dateFrom}&date_to=${dateTo}&phone=${phone}`,
                    method: 'GET',
                    success: function (res) {
                        let tbody = '';
                        res.forEach((user, index) => {
                            tbody += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${user.chat_id}</td>
                                    <td>${user.first_name || ''}</td>
                                    <td>${user.second_name || ''}</td>
                                    <td>${user.uname || ''}</td>
                                    <td>${user.phone || ''}</td>
                                    <td>${user.step || ''}</td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" class="switch-input" data-user-id="${user.id}" ${user.isactive ? 'checked' : ''}>
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on"></span>
                                                <span class="switch-off"></span>
                                            </span>
                                        </label>
                                    </td>
                                    <td>${user.country ? user.country.name.ru + ',' : ''} ${user.city ? user.city.name.ru : ''}</td>
                                    <td>${user.created_at ? moment(user.created_at).format('YYYY-MM-DD HH:mm:ss') : ''}</td>
                                    <td>${user.last_activity ? moment(user.last_activity).format('YYYY-MM-DD HH:mm:ss') : ''}</td>
                                    <td>
                                        <button class="btn btn-sm btn-icon" onclick="location.href='{{ route('bot.user.journey', '') }}/${user.id}'">
                                            <i class="bx bx-show"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                        $('.datatables-users tbody').html(tbody);
                    },
                    error: function (error) {
                        console.error('Error fetching users:', error);
                    }
                });
            }

            fetchUsers();

            $('#from, #to, #phone').on('input change', fetchUsers);

            $('#export-statistics').click(function () {
                const dateFrom = $('#from').val();
                const dateTo = $('#to').val();
                const phone = $('#phone').val();
                window.location.href = `/bot-users/statistics?date_from=${dateFrom}&date_to=${dateTo}&phone=${phone}`;
            })

            $(document).on('change', '.switch-input', function () {
                let userId = $(this).data('user-id');
                let isActive = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                    url: `/api/bot-users/${userId}/is-active`,
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        isactive: isActive
                    },
                    success: function (res) {
                        console.log('User status updated:', res);
                    },
                    error: function (error) {
                        console.error('Error updating user status:', error);
                    }
                });
            });
        });
    </script>
@endsection
