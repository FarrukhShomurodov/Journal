@extends('admin.layouts.app')

@section('title')
    <title>Journal | Редактировать пользователя</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light"><a class="text-muted"
                                             href="{{route('users.index')}}">Пользователи</a> /</span>Редактировать
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

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Редактировать</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="mb-3">
                    <label class="form-label" for="basic-default-fullname">Имя</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           id="basic-default-fullname" placeholder="Имя" value="{{ $user->name }}"
                           required>
                </div>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="mb-3">
                    <label class="form-label" for="basic-default-fullname">Фамилия</label>
                    <input type="text" name="second_name" class="form-control @error('second_name') is-invalid @enderror"
                           id="basic-default-fullname" placeholder="Фамилия"
                           value="{{ $user->second_name }}" required>
                </div>
                @error('second_name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="mb-3">
                    <label class="form-label" for="basic-default-message">Логин</label>
                    <input type="text" name="login" class="form-control @error('login') is-invalid @enderror" value="{{ $user->login }}"
                           id="basic-default-fullname" placeholder="Логин" required>
                </div>
                @error('login')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="mb-3">
                    <label for="formFile" class="form-label">Пароль</label>
                    <input class="form-control house-photo @error('password') is-invalid @enderror" type="password" name="password" id="formFile"
                           placeholder="Пароль">
                </div>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror

{{--                Role--}}
                <input type="hidden" name="role_id" value="2">

                <button type="submit" class="btn btn-warning ">Редактировать</button>
            </form>
        </div>
    </div>
@endsection
