@extends('admin.layouts.app')

@section('title')
    <title>Journal - Типы заболеваний</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light">Типы заболеваний</span>
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
            <h5 class="card-header">Типы заболеваний</h5>
            <a href="{{  route('diseaseTypes.create') }}" class="btn btn-primary"
               style="margin-right: 22px;">Создать</a>
        </div>

        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                <tr>
                    <th>Название</th>
                    <th>Кол-во просмотров</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($diseaseTypes as $diseaseType)
                    <tr>
                        <td>
                            @foreach($diseaseType->name as $lang => $name)
                                <div><b>{{ strtoupper($lang) }}:</b> {{ $name }}</div>
                            @endforeach
                        </td>
                        <td>
                            {{ $diseaseType->views()->count() }}
                        </td>
                        <td>
                            <div class="d-inline-block text-nowrap">
                                <button class="btn btn-sm btn-icon"
                                        onclick="location.href='{{ route('diseaseTypes.edit', $diseaseType->id) }}'"><i
                                        class="bx bx-edit"></i></button>
                                <form action="{{ route('diseaseTypes.destroy', $diseaseType->id) }}" method="POST"
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
