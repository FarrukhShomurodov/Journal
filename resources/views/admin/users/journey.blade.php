@extends('admin.layouts.app')

@section('title')
    <title>Journal - Путь пользователя бота</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light"><a class="text-muted" href="{{ route('bot.users') }}">Пользователи бота</a> /</span>Путь
    </h6>

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Шаги пользователи бота</h5>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                <tr>
                    <th>id</th>
                    <th>Название</th>
                    <th>Дата</th>
                </tr>
                </thead>
                <tbody>

                @php
                    $journeyCount = 1
                @endphp

                @foreach($journeys as $journey)
                    <tr>
                        <td>{{ $journeyCount++ }}</td>
                        <td>{{ $journey->event_name }}</td>
                        <td>{{ $journey->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
