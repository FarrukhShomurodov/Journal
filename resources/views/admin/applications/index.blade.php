@extends('admin.layouts.app')

@section('title')
    <title>Journal - Заявки</title>
@endsection


<?php
$firstDayOfMonth = date('Y-m-01');
$today = date('Y-m-d');
?>

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light">Заявки</span>
    </h6>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-solid-danger alert-dismissible d-flex align-items-center" role="alert">
                {{ $error }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endforeach
    @endif

    <div class="card">
        <div class="d-flex justify-content-between align-items-center p-3">
            <h5 class="card-header">Заявки</h5>
            <form id="filter-form">
                <div class="d-flex">
                    <div class="d-flex flex-row align-items-center me-2">
                        <label for="from" class="me-2">От:</label>
                        <input id="from" name="date_from" type="date" class="form-control" value="{{$firstDayOfMonth}}">
                    </div>
                    <div class="d-flex flex-row align-items-center me-2">
                        <label for="to" class="me-2">До:</label>
                        <input id="to" name="date_to" type="date" class="form-control" value="{{$today}}">
                    </div>

                    <div class="me-2">
                        <input id="phone" name="phone" type="text" class="form-control" placeholder="Телефон">
                    </div>

                    <div class="me-2">
                        <select class="select2 form-control" name="specialization-id" id="specialization-id">
                            <option value="all">Специализации</option>
                            @foreach($specializations as $specialization)
                                <option value="{{ $specialization->id }}">{{ $specialization->name['ru'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="me-2">
                        <select class="select2 form-control" name="clinic-id" id="clinic-id">
                            <option value="all">Клиники</option>
                            @foreach($clinics as $clinic)
                                <option value="{{ $clinic->id }}">{{ $clinic->name['ru'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Пользователь бота</th>
                    <th>Клиника</th>
                    <th>Текст</th>
                    <th>Рассмотрено</th>
                    <th>Дата создание</th>
                </tr>
                </thead>
                <tbody id="application-table-body">
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            function filterApplications() {
                const formData = $('#filter-form').serialize();

                $.ajax({
                    url: '/api/applications/filter',
                    method: 'GET',
                    data: formData,
                    success: function (applications) {
                        const tableBody = $('#application-table-body');
                        tableBody.empty();

                        applications.forEach(application => {
                            tableBody.append(`
                    <tr>
                        <td>${application.id}</td>
                        <td>${application.bot_user.phone}</td>
                        <td>${application.clinic.name.ru}</td>
                        <td>${application.text}</td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-application-id="${application.id}" ${application.is_reviewed ? 'checked' : ''}>
                                <span class="switch-toggle-slider">
                                    <span class="switch-on"></span>
                                    <span class="switch-off"></span>
                                </span>
                            </label>
                        </td>
                        <td>${moment(application.created_at).format('YYYY-MM-DD HH:mm:ss')}</td>
                    </tr>
                `);
                        });
                    },
                    error: function (error) {
                        console.error('Error fetching applications:', error);
                    }
                });
            }

            filterApplications()

            $('#from, #to, #specialization-id, #clinic-id, #phone').on('input change', filterApplications);

            $(document).on('change', '.switch-input', function () {
                const applicationId = $(this).data('application-id');
                const isActive = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                    url: `/api/application/${applicationId}/is-reviewed`,
                    method: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        is_reviewed: isActive
                    },
                    success: function () {
                        console.log('Status updated');
                    },
                    error: function (error) {
                        console.error('Error updating status:', error);
                    }
                });
            });
        });
    </script>
@endsection
