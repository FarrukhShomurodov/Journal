@extends('admin.layouts.app')

@section('title')
    <title>Journal - Редактировать клинику</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light"><a class="text-muted"
                                             href="{{ route('clinics.index') }}">Клиника</a> /</span>Редактировать
    </h6>
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Редактировать</h5>
        </div>
        @if ($errors->any())
            <div class="alert alert-solid-danger" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card-body">
            <form action="{{ route('clinics.update', $clinic->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label" for="name_ru">Название RU</label>
                    <input type="text" name="name[ru]" class="form-control @error('name.ru') is-invalid @enderror"
                           placeholder="Название RU"
                           id="name_ru" value="{{ $clinic->name['ru'] }}" required>
                    @error('name.ru')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_en">Название EN</label>
                    <input type="text" name="name[en]" class="form-control @error('name.en') is-invalid @enderror"
                           placeholder="Название EN"
                           id="name_en" value="{{ $clinic->name['en'] }}" required>
                    @error('name.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_uz">Название UZ</label>
                    <input type="text" name="name[uz]" class="form-control @error('name.uz') is-invalid @enderror"
                           placeholder="Название UZ"
                           id="name_uz" value="{{  $clinic->name['uz'] }}" required>
                    @error('name.uz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_kz">Название KZ</label>
                    <input type="text" name="name[kz]" class="form-control @error('name.kz') is-invalid @enderror"
                           placeholder="Название KZ"
                           id="name_kz" value="{{ $clinic->name['kz'] }}" required>
                    @error('name.kz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_tj">Название TJ</label>
                    <input type="text" name="name[tj]" class="form-control @error('name.tj') is-invalid @enderror"
                           placeholder="Название TJ"
                           id="name_tj" value="{{ $clinic->name['tj'] }}" required>
                    @error('name.tj')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_ru">Описание RU</label>
                    <textarea name="description[ru]"
                              class="form-control @error('description.ru') is-invalid @enderror" id="description_ru"
                              placeholder="Описание RU"
                              required>{{ $clinic->description['ru'] }}</textarea>
                    @error('description.ru')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_en">Описание EN</label>
                    <textarea name="description[en]"
                              class="form-control @error('description.en') is-invalid @enderror" id="description_en"
                              placeholder="Описание EN"
                              required>{{  $clinic->description['en'] }}</textarea>
                    @error('description.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_uz">Описание UZ</label>
                    <textarea name="description[uz]"
                              class="form-control @error('description.uz') is-invalid @enderror" id="description_uz"
                              placeholder="Описание UZ"
                              required>{{ $clinic->description['uz'] }}</textarea>
                    @error('description.uz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_kz">Описание KZ</label>
                    <textarea name="description[kz]"
                              class="form-control @error('description.kz') is-invalid @enderror" id="description_kz"
                              placeholder="Описание KZ"
                              required>{{ $clinic->description['kz'] }}</textarea>
                    @error('description.kz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_tj">Описание TJ</label>
                    <textarea name="description[tj]"
                              class="form-control @error('description.tj') is-invalid @enderror" id="description_tj"
                              placeholder="Описание TJ"
                              required>{{ $clinic->description['tj'] }}</textarea>
                    @error('description.tj')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="working_hours">График работы</label>
                    <input type="text" name="working_hours"
                           class="form-control @error('working_hours') is-invalid @enderror" id="working_hours"
                           placeholder="График работы"
                           value="{{ $clinic->working_hours }}">
                    @error('working_hours')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="location_link">Локация (ссылка)</label>
                    <input type="text" name="location_link"
                           class="form-control @error('location_link') is-invalid @enderror" id="location_link"
                           value="{{$clinic->location_link}}"
                           placeholder="Локация (ссылка)">
                    @error('location_link')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                <div class="mb-3">
                    <label class="form-label" for="rating">Рейтинг</label>
                    <input type="text" name="rating"
                           class="form-control @error('rating') is-invalid @enderror" id="rating"
                           value="{{$clinic->rating}}"
                           placeholder="Рейтинг"
                           min="1" max="5" step="0.1" required>
                    @error('rating')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="specialization_id">Специализация</label>
                    <select name="specialization[]" class="form-control @error('specialization') is-invalid @enderror"
                            id="specialization_id" required multiple>
                        <option value="">Выберите специализацию</option>
                        @foreach($specializations as $specialization)
                            <option value="{{ $specialization->id }}"
                                {{ in_array($specialization->id, old('specialization', $clinic->specializations->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $specialization->name['ru'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('specialization')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="disease_type_id">Тип болезни</label>
                    <select name="disease_type[]" class="form-control @error('disease_type') is-invalid @enderror"
                            id="disease_type_id" required multiple>
                        <option value="">Выберите тип болезни</option>
                        @foreach($diseaseTypes as $diseaseType)
                            <option value="{{ $diseaseType->id }}"
                                {{ in_array($diseaseType->id, old('disease_type', $clinic->diseaseTypes->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $diseaseType->name['ru'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('disease_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="imageInput" class="form-label">Загрузить фото</label>
                    <input type="file" name="photos[]" id="imageInput" class="form-control" multiple>
                </div>
                <div id="imagePreview" class="mb-3 main__td">
                    @if($clinic->images)
                        @foreach(json_decode($clinic->images) as $photo)
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
                        @foreach($clinic->contacts['type'] as $index => $contactType)
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
                                        <label for="type_value">Значение Типа:</label>
                                        <input type="text" name="contacts[type_value][{{ $index }}]"
                                               id="type_value"
                                               class="form-control @error('contacts.type_value.' . $index) is-invalid @enderror"
                                               placeholder="(e.g., 99890000000)"
                                               value="{{ $clinic->contacts['type_value'][$index] }}">
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
                            id="add-contact">Добавить контакт
                    </button>
                </div>
                <button type="submit" class="btn btn-warning">Редактировать</button>
            </form>
        </div>
    </div>
@endsection
