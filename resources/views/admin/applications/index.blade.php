@extends('admin.layouts.app')

@section('title')
    <title>Journal - Заявки</title>
@endsection

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
            <form method="GET" action="">
                <div class="d-flex">
                    <div class="d-flex flex-row align-items-center " style="margin-right: 10px">
                        <label for="from" class="me-2">От: </label>
                        <input onchange="this.form.submit()" id="from" name="date_from" type="date" class="form-control"
                               value="{{ $dateFrom }}">
                    </div>
                    <div class="d-flex flex-row align-items-center " style="margin-right: 10px">
                        <label for="to" class="me-2">До: </label>
                        <input onchange="this.form.submit()" id="to" name="date_to" type="date" class="form-control"
                               value="{{ $dateTo }}">
                    </div>
                    <div class="me-2">
                        <select id="select2"
                                class="select2 form-select"
                                name="clinic-id"
                                onchange="this.form.submit()"
                                tabindex="-1" aria-hidden="true" style="margin-right: 10px">
                            <option value="all" {{ request('clinic-id') == 'all' ? 'selected' : '' }}>
                                Клиники
                            </option>
                            @foreach($clinics as $clinic)
                                <option value="{{ $clinic->id }}"
                                    {{ request('clinic-id') == $clinic->id ? 'selected' : '' }}>
                                    {{ $clinic->name['ru'] }}
                                </option>
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
                    <th>Расмотрено</th>
                    <th>Дата создание</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($applications as $application)
                    <tr>
                        <td>{{ $application->id }}</td>
                        <td>{{ $application->botUser->phone }}</td>
                        <td>{{ $application->clinic->name['ru'] }}</td>
                        <td>{{ $application->text }}</td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="switch-input" data-application-id="{{ $application->id }}"
                                       @if($application->is_reviewed) checked @endif>
                                <span class="switch-toggle-slider">
                                    <span class="switch-on"></span>
                                    <span class="switch-off"></span>
                                </span>
                            </label>
                        </td>
                        <td>{{ $application->created_at }}</td>
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
                let applicationId = $(this).data('application-id');
                let isActive = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                    url: `/api/application/${applicationId}/is-reviewed`,
                    method: 'put',
                    data: {
                        _token: '{{ csrf_token() }}',
                        is_reviewed: isActive
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
