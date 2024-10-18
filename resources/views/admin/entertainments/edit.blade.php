@extends('admin.layouts.app')

@section('title')
    <title>Journal - Редактировать развлечения</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light"><a class="text-muted"
                                             href="{{ route('entertainments.index') }}">Развлечения</a> /</span>Редактировать
    </h6>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-solid-danger alert-dismissible d-flex align-items-center" role="alert">
                {{ $error }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endforeach
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Редактировать</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('entertainments.update', $entertainment->id) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label" for="name_ru">Название RU</label>
                    <input type="text" name="name[ru]" class="form-control @error('name.ru') is-invalid @enderror"
                           placeholder="Название RU"
                           id="name_ru" value="{{ $entertainment->name['ru'] }}" required>
                    @error('name.ru')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_en">Название EN</label>
                    <input type="text" name="name[en]" class="form-control @error('name.en') is-invalid @enderror"
                           placeholder="Название EN"
                           id="name_en" value="{{ $entertainment->name['en'] }}" required>
                    @error('name.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_uz">Название UZ</label>
                    <input type="text" name="name[uz]" class="form-control @error('name.uz') is-invalid @enderror"
                           placeholder="Название UZ"
                           id="name_uz" value="{{ $entertainment->name['uz'] }}" required>
                    @error('name.uz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_kz">Название KZ</label>
                    <input type="text" name="name[kz]" class="form-control @error('name.kz') is-invalid @enderror"
                           placeholder="Название KZ"
                           id="name_kz" value="{{ $entertainment->name['kz'] }}" required>
                    @error('name.kz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_tj">Название TJ</label>
                    <input type="text" name="name[tj]" class="form-control @error('name.tj') is-invalid @enderror"
                           placeholder="Название TJ"
                           id="name_tj" value="{{ $entertainment->name['tj'] }}" required>
                    @error('name.tj')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_ru">Описание RU</label>
                    <textarea name="description[ru]"
                              class="form-control @error('description.ru') is-invalid @enderror" id="description_ru"
                              placeholder="Описание RU"
                              required>{{ $entertainment->description['ru'] }}</textarea>
                    @error('description.ru')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_en">Описание EN</label>
                    <textarea name="description[en]"
                              class="form-control @error('description.en') is-invalid @enderror" id="description_en"
                              placeholder="Описание EN"
                              required>{{ $entertainment->description['en'] }}</textarea>
                    @error('description.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_uz">Описание UZ</label>
                    <textarea name="description[uz]"
                              class="form-control @error('description.uz') is-invalid @enderror" id="description_uz"
                              placeholder="Описание UZ"
                              required>{{ $entertainment->description['uz'] }}</textarea>
                    @error('description.uz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_kz">Описание KZ</label>
                    <textarea name="description[kz]"
                              class="form-control @error('description.kz') is-invalid @enderror" id="description_kz"
                              placeholder="Описание KZ"
                              required>{{ $entertainment->description['kz'] }}</textarea>
                    @error('description.kz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_tj">Описание TJ</label>
                    <textarea name="description[tj]"
                              class="form-control @error('description.tj') is-invalid @enderror" id="description_tj"
                              placeholder="Описание TJ"
                              required>{{ $entertainment->description['tj'] }}</textarea>
                    @error('description.tj')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="working_hours">График работы</label>
                    <input type="text" name="working_hours"
                           class="form-control @error('working_hours') is-invalid @enderror" id="working_hours"
                           placeholder="График работы"
                           value="{{ $entertainment->working_hours }}">
                    @error('working_hours')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="price_from">Цена от</label>
                    <input type="number" step="0.01" name="price_from"
                           class="form-control @error('price_from') is-invalid @enderror" id="price_from"
                           placeholder="Цена от"
                           value="{{ round($entertainment->price_from) }}">
                    @error('price_from')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="price_to">Цена до</label>
                    <input type="number" step="0.01" name="price_to"
                           class="form-control @error('price_to') is-invalid @enderror" id="price_to"
                           placeholder="Цена до"
                           value="{{ round($entertainment->price_to) }}">
                    @error('price_to')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="location_link">Локация (ссылка)</label>
                    <input type="text" name="location_link"
                           class="form-control @error('location_link') is-invalid @enderror" id="location_link"
                           value="{{$entertainment->location_link}}"
                           placeholder="Локация (ссылка)">
                    @error('location_link')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="imageInput" class="form-label">Загрузить фото</label>
                    <input type="file" name="photos[]" id="imageInput" class="form-control" multiple>
                </div>
                <div id="imagePreview" class="mb-3 main__td">
                    @if($entertainment->images)
                        @foreach(json_decode($entertainment->images) as $photo)
                            <div class="image-container td__img" data-photo-path="{{ $photo->url }}">
                                <img src="{{ asset('storage/' . $photo->url) }}" alt="Court Image"
                                     class="uploaded-image">
                                <button type="button" class="btn btn-danger btn-sm delete-image"
                                        data-photo-path="{{ $photo->url }}"> Удалить
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Контакты</label>
                    <div id="contacts-container">
                        @foreach($entertainment->contacts['type'] as $index => $contactType)
                            <div class="contact-group mb-2">
                                <div class="d-flex">
                                    <div class="me-2">
                                        <label for="type">Тип контакта:</label>
                                        <input type="text" name="contacts[type][{{ $index }}]"
                                               id="type"
                                               class="form-control @error('contacts.type.' . $index) is-invalid @enderror"
                                               placeholder="(e.g., Phone)"
                                               value="{{ $contactType }}">
                                        @error('contacts.type.' . $index)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="type_val">Значение Типа:</label>
                                        <input type="text" name="contacts[type_value][{{ $index }}]"
                                               id="type_val"
                                               class="form-control @error('contacts.type_value.' . $index) is-invalid @enderror"
                                               placeholder="(e.g., 99890000000)"
                                               value="{{ $entertainment->contacts['type_value'][$index] }}">
                                        @error('contacts.type_value.' . $index)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @if($index > 1)
                                        <button type="button"
                                                class="btn btn-danger mt-3 ms-2 delete-contact">Удалить
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-secondary mt-2"
                            id="add-contact">Добавить Контакт
                    </button>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-warning">Редактировать</button>
                </div>
            </form>
        </div>
    </div>
@endsection
