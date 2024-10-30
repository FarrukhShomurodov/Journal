@extends('admin.layouts.app')

@section('title')
    <title>Journal - Города</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light">Города</span>
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
            <h5 class="card-header">Города</h5>
            <a href="{{  route('cities.create') }}" class="btn btn-primary"
               style="margin-right: 22px;">Создать</a>
        </div>

        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                <tr>
                    <th>Название</th>
                    <th>Страна</th>
                    <th>Кол-во выбранных</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($cities as $city)
                    <tr>
                        <td>
                            @foreach($city->name as $lang => $name)
                                <div><b>{{ strtoupper($lang) }}:</b> {{ $name }}</div>
                            @endforeach
                        </td>
                        <td>
                            {{$city->country->name['ru']}}
                        </td>
                        <td>
                            {{ $city->users() ? $city->users()->count() : '0' }}
                        </td>
                        <td>
                            <div class="d-inline-block text-nowrap">
                                <button class="btn btn-sm btn-icon"
                                        onclick="location.href='{{ route('cities.edit', $city->id) }}'"><i
                                        class="bx bx-edit"></i></button>
                                <form action="{{ route('cities.destroy', $city->id) }}" method="POST"
                                      style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-icon delete-record"><i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
