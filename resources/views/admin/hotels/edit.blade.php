@extends('admin.layouts.app')

@section('title')
    <title>Journal - Редактировать отель</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light"><a class="text-muted"
                                             href="{{ route('hotels.index') }}">Отели</a> /</span>Редактировать
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
            <form action="{{ route('hotels.update', $hotel->id) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label" for="name_ru">Название RU</label>
                    <input type="text" name="name[ru]" class="form-control @error('name.ru') is-invalid @enderror"
                           placeholder="Название RU"
                           id="name_ru" value="{{ $hotel->name['ru'] }}" required>
                    @error('name.ru')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_en">Название EN</label>
                    <input type="text" name="name[en]" class="form-control @error('name.en') is-invalid @enderror"
                           placeholder="Название EN"
                           id="name_en" value="{{ $hotel->name['en'] }}" required>
                    @error('name.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_uz">Название UZ</label>
                    <input type="text" name="name[uz]" class="form-control @error('name.uz') is-invalid @enderror"
                           placeholder="Название UZ"
                           id="name_uz" value="{{ $hotel->name['uz'] }}" required>
                    @error('name.uz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_kz">Название KZ</label>
                    <input type="text" name="name[kz]" class="form-control @error('name.kz') is-invalid @enderror"
                           placeholder="Название KZ"
                           id="name_kz" value="{{ $hotel->name['kz'] }}" required>
                    @error('name.kz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_tj">Название TJ</label>
                    <input type="text" name="name[tj]" class="form-control @error('name.tj') is-invalid @enderror"
                           placeholder="Название TJ"
                           id="name_tj" value="{{ $hotel->name['tj'] }}" required>
                    @error('name.tj')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_ru">Описание RU</label>
                    <textarea name="description[ru]"
                              class="form-control @error('description.ru') is-invalid @enderror" id="description_ru"
                              placeholder="Описание RU"
                              required>{{ $hotel->description['ru'] }}</textarea>
                    @error('description.ru')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_en">Описание EN</label>
                    <textarea name="description[en]"
                              class="form-control @error('description.en') is-invalid @enderror" id="description_en"
                              placeholder="Описание EN"
                              required>{{  $hotel->description['en'] }}</textarea>
                    @error('description.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_uz">Описание UZ</label>
                    <textarea name="description[uz]"
                              class="form-control @error('description.uz') is-invalid @enderror" id="description_uz"
                              placeholder="Описание UZ"
                              required>{{ $hotel->description['uz'] }}</textarea>
                    @error('description.uz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_kz">Описание KZ</label>
                    <textarea name="description[kz]"
                              class="form-control @error('description.kz') is-invalid @enderror" id="description_kz"
                              placeholder="Описание KZ"
                              required>{{ $hotel->description['kz'] }}</textarea>
                    @error('description.kz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_tj">Описание TJ</label>
                    <textarea name="description[tj]"
                              class="form-control @error('description.tj') is-invalid @enderror" id="description_tj"
                              placeholder="Описание TJ"
                              required>{{ $hotel->description['tj'] }}</textarea>
                    @error('description.tj')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="working_hours">График работы</label>
                    <input type="text" name="working_hours"
                           class="form-control @error('working_hours') is-invalid @enderror" id="working_hours"
                           placeholder="График работы"
                           value="{{ $hotel->working_hours }}" required>
                    @error('working_hours')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="price_from">Цена от</label>
                    <input type="number" step="0.01" name="price_from"
                           class="form-control @error('price_from') is-invalid @enderror" id="price_from"
                           placeholder="Цена от"
                           value="{{ round($hotel->price_from) }}" required>
                    @error('price_from')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="price_to">Цена до</label>
                    <input type="number" step="0.01" name="price_to"
                           class="form-control @error('price_to') is-invalid @enderror" id="price_to"
                           placeholder="Цена до"
                           value="{{ round($hotel->price_to) }}" required>
                    @error('price_to')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="location_link">Локация (ссылка)</label>
                    <input type="text" name="location_link"
                           class="form-control @error('location_link') is-invalid @enderror" id="location_link"
                           value="{{ $hotel->location_link }}"
                           placeholder="Локация (ссылка)" required>
                    @error('location_link')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="imageInput" class="form-label">Загрузить фото</label>
                    <input type="file" name="photos[]" id="imageInput" class="form-control" multiple>
                </div>
                <div id="imagePreview" class="mb-3 main__td">
                    @if($hotel->images)
                        @foreach(json_decode($hotel->images) as $photo)
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
                        @foreach($hotel->contacts['type'] as $index => $contactType)
                            <div class="contact-group mb-3 border p-3 rounded">
                                <div class="row g-3 align-items-center">
                                    @foreach(['ru', 'en', 'uz', 'kz', 'tj'] as $lang)
                                        <div class="col-md-6">
                                            <label for="type-{{ $lang }}">Тип контакта ({{ strtoupper($lang) }}):</label>
                                            <input type="text" name="contacts[type][{{ $index }}][{{ $lang }}]"
                                                   id="type-{{ $lang }}"
                                                   class="form-control @error('contacts.type.' . $index . '.' . $lang) is-invalid @enderror"
                                                   placeholder="(e.g., Телефон)"
                                                   value="{{ $contactType[$lang] ?? '' }}" required>
                                            @error('contacts.type.' . $index . '.' . $lang)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach

                                    <div class="col-md-6">
                                        <label for="type_val-{{ $index }}">Значение контакта:</label>
                                        <input type="text" name="contacts[type_value][{{ $index }}]"
                                               id="type_val-{{ $index }}"
                                               class="form-control @error('contacts.type_value.' . $index) is-invalid @enderror"
                                               placeholder="(e.g., 99890000000)"
                                               value="{{ $hotel->contacts['type_value'][$index] }}" required>
                                        @error('contacts.type_value.' . $index)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 text-end">
                                        @if($index > 1)
                                            <button type="button" class="btn btn-danger delete-contact">Удалить контакт</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-secondary mt-3" id="add-contact">Добавить контакт</button>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-warning">Редактировать</button>
                </div>
            </form>
        </div>
    </div>
@endsection
