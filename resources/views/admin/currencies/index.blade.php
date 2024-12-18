@extends('admin.layouts.app')

@section('title')
    <title>Journal - Валюты</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light">Валюты</span>
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

    <div class="card">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-header">Валюты</h5>
        </div>

        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                <tr>
                    <th>Название</th>
                    <th>Код</th>
                    <th>Валюта</th>
                    <th>Курс</th>
                    <th>ДАТА АКТУАЛЬНОСТИ</th>
                    <th>Кол-во просмотров</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($currencies as $currency)
                    <tr>
                        <td>
                            @foreach(json_decode($currency->name) as $lang => $name)
                                <div><b>{{ strtoupper($lang) }}:</b> {{ $name }}</div>
                            @endforeach
                        </td>
                        <td>{{ $currency->code }}</td>
                        <td>{{ $currency->ccy }}</td>
                        <td>{{ number_format($currency->rate, 2) }}</td>
                        <td>{{ $currency->relevance_date->format('Y-m-d') }}</td>
                        <td>{{ $currency->views()->count() }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
