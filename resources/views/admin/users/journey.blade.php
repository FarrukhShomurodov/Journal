@extends('admin.layouts.app')

@section('title')
    <title>Journal - Путь пользователя бота</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light"><a class="text-muted" href="{{ route('bot.users') }}">Пользователи бота</a> /</span>Путь
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

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="m-0">Шаги пользователя бота</h5>
        </div>
        <div class="card-body">
            <form class="d-flex flex-column gap-3" method="POST" action="{{ route('bot.sendMessage') }}">
                @csrf
                <div class="form-group">
                    <label for="text" class="form-label">Сообщение</label>
                    <textarea name="text" id="text" rows="4"
                              class="form-control @error('text') is-invalid @enderror"
                              placeholder="Введите сообщение..." required></textarea>
                    <input type="hidden" name="chat_ids[]" value="{{$user->chat_id}}">
                    @error('text')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Отправить сообщение</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Дата</th>
                </tr>
                </thead>
                <tbody>
                @php $journeyCount = 1; @endphp
                @foreach($journeys as $journey)
                    <tr>
                        <td>{{ $journeyCount++ }}</td>
                        <td>{{ $journey->event_name }}</td>
                        <td>{{ $journey->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
