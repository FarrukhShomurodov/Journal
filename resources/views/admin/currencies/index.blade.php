@extends('admin.layouts.app')

@section('title')
    <title>Journal - Валюты</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light">Валюты</span>
    </h6>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-solid-danger alert-dismissible d-flex align-items-center" role="alert">
                {{ $error }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endforeach
    @endif

    @if(session()->has('success'))
        <div class="alert alert-solid-success alert-dismissible d-flex align-items-center" role="alert">
            {{ session()->get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
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
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
