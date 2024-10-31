@extends('admin.layouts.app')

@section('title')
    <title>Journal | Создать пользователя</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light"><a class="text-muted"
                                             href="{{route('users.index')}}">Пользователи</a> /</span>Создать
        пользователя
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
            <h5 class="mb-0">Создать</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="basic-default-fullname">Имя</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           id="basic-default-fullname" placeholder="Имя" required>
                </div>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="mb-3">
                    <label class="form-label" for="basic-default-secondname">Фамилия</label>
                    <input type="text" name="second_name"
                           class="form-control @error('second_name') is-invalid @enderror"
                           id="basic-default-secondname" placeholder="Фамилия" required>
                </div>
                @error('second_name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="mb-3">
                    <label class="form-label" for="basic-default-login">Логин</label>
                    <input type="text" name="login" class="form-control @error('login') is-invalid @enderror"
                           id="basic-default-login" placeholder="Логин" required>
                </div>
                @error('login')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                {{--                 Role--}}
                <input type="hidden" name="role_id" value="2">

                <div class="mb-3">
                    <label for="formFile" class="form-label">Пароль</label>
                    <input class="form-control @error('password') is-invalid @enderror" type="password" name="password"
                           id="formFile"
                           placeholder="Пароль" required>
                </div>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <button type="submit" class="btn btn-primary">Сохранить</button>
            </form>
        </div>
    </div>
@endsection
