@extends('admin.layouts.app')

@section('title')
    <title>{{ 'Findz - ' . __('establishment.establishments') }}</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light">@lang('currency.currencies')</span>
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
            <h5 class="card-header">@lang('establishment.establishments')</h5>
            <a href="{{  route('establishments.create') }}" class="btn btn-primary"
               style="margin-right: 22px;">@lang('commands.create')</a>
        </div>

        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                <tr>
                    <th>@lang('establishment.name')</th>
                    <th>@lang('establishment.category')</th>
                    <th>@lang('establishment.actions')</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($establishments as $establishment)
                    <tr>
                        <td>
                            @foreach($establishment->name as $lang => $name)
                                <div><b>{{ strtoupper($lang) }}:</b> {{ $name }}</div>
                            @endforeach
                        </td>
                        <td>{{ $establishment->category->name['ru'] }}</td>
                        <td>
                            <div class="d-inline-block text-nowrap">
                                <button class="btn btn-sm btn-icon"
                                        onclick="location.href='{{ route('establishments.edit', $establishment->id) }}'"><i
                                        class="bx bx-edit"></i></button>
                                <form action="{{ route('establishments.destroy', $establishment->id) }}" method="POST"
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
