@extends('admin.layouts.app')

@section('title')
    <title>Journal - Клиники</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light">Клиники</span>
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
            <h5 class="card-header">Клиники</h5>
            <a href="{{  route('clinics.create') }}" class="btn btn-primary"
               style="margin-right: 22px;">Создать</a>
        </div>

        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                <tr>
                    <th>Название</th>
                    <th>Специализация</th>
                    <th>Тип болезни</th>
                    <th>Ретинг</th>
                    <th>Кол-во просмотров</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($clinics as $clinic)
                    <tr>
                        <td>
                            @foreach($clinic->name as $lang => $name)
                                <div><b>{{ strtoupper($lang) }}:</b> {{ $name }}</div>
                            @endforeach
                        </td>
                        <td>
                            @foreach($clinic->specializations as $specialization)
                                <div> {{ $specialization->name['ru'] }}</div>
                            @endforeach
                        </td>
                        <td>
                            @foreach($clinic->diseaseTypes as $diseaseType)
                                <div> {{ $diseaseType->name['ru'] }}</div>
                            @endforeach
                        </td>
                        <td>
                            {{ $clinic->rating }}
                        </td>
                        <td>
                            {{ $clinic->views()->count() }}
                        </td>
                        <td>
                            <div class="d-inline-block text-nowrap">
                                <button class="btn btn-sm btn-icon"
                                        onclick="location.href='{{ route('clinics.edit', $clinic->id) }}'"><i
                                        class="bx bx-edit"></i></button>
                                @role('admin')
                                <form action="{{ route('clinics.destroy', $clinic->id) }}" method="POST"
                                      style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-icon delete-record"><i class="bx bx-trash"></i>
                                    </button>
                                </form>
                                @endrole
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
