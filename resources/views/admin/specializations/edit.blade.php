@extends('admin.layouts.app')

@section('title')
    <title>Journal - Редактировать специализацию</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light"><a class="text-muted" href="{{ route('specializations.index') }}">Специализации</a> /</span>Редактировать
    </h6>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Редактировать</h5>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card-body">
            <form action="{{ route('specializations.update', $specialization->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label" for="name_ru">Название RU</label>
                    <input type="text" name="name[ru]" class="form-control @error('name.ru') is-invalid @enderror"
                           placeholder="Название RU"
                           id="name_ru" value="{{ $specialization->name['ru'] }}" required>
                    @error('name.ru')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_en">Название EN</label>
                    <input type="text" name="name[en]" class="form-control @error('name.en') is-invalid @enderror"
                           placeholder="Название EN"
                           id="name_en" value="{{ $specialization->name['en'] }}" required>
                    @error('name.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_uz">Название UZ</label>
                    <input type="text" name="name[uz]" class="form-control @error('name.uz') is-invalid @enderror"
                           placeholder="Название UZ"
                           id="name_uz" value="{{ $specialization->name['uz'] }}" required>
                    @error('name.uz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_kz">Название KZ</label>
                    <input type="text" name="name[kz]" class="form-control @error('name.kz') is-invalid @enderror"
                           placeholder="Название KZ"
                           id="name_kz" value="{{ $specialization->name['kz'] }}" required>
                    @error('name.kz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_tj">Название TJ</label>
                    <input type="text" name="name[tj]" class="form-control @error('name.tj') is-invalid @enderror"
                           placeholder="Название TJ"
                           id="name_tj" value="{{ $specialization->name['tj'] }}" required>
                    @error('name.tj')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="rating">Рейтинг</label>
                    <input type="text" name="rating"
                           class="form-control @error('rating') is-invalid @enderror" id="rating"
                           value="{{$specialization->rating}}"
                           placeholder="Рейтинг"
                           min="1" max="5" step="0.1" required>
                    @error('rating')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-warning">Редактировать</button>
            </form>
        </div>
    </div>
@endsection
