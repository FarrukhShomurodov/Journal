@extends('admin.layouts.app')

@section('title')
    <title>{{ 'Findz - ' . __('clinic.clinics') }}</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light">@lang('clinic.clinics')</span>
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
            <h5 class="card-header">@lang('clinic.clinics')</h5>
            <a href="{{  route('clinics.create') }}" class="btn btn-primary"
               style="margin-right: 22px;">@lang('commands.create')</a>
        </div>

        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>@lang('clinic.name')</th>
                    <th>@lang('clinic.specialization')</th>
                    <th>@lang('clinic.diseaseType')</th>
                    <th>Ретинг</th>
                    <th>@lang('clinic.actions')</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($clinics as $clinic)
                    <tr>
                        <td>{{ $clinic->id }}</td>
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
                            <div class="d-inline-block text-nowrap">
                                <button class="btn btn-sm btn-icon"
                                        onclick="location.href='{{ route('clinics.edit', $clinic->id) }}'"><i
                                        class="bx bx-edit"></i></button>
                                <form action="{{ route('clinics.destroy', $clinic->id) }}" method="POST"
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
