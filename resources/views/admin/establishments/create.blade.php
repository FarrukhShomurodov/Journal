@extends('admin.layouts.app')

@section('title')
    <title>Journal - Создать заведения</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light"><a class="text-muted"
                                             href="{{ route('establishments.index') }}">Заведения</a> /</span>Создать
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
            <h5 class="mb-0">Создать</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('establishments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="name_ru">Название RU</label>
                    <input type="text" name="name[ru]" class="form-control @error('name.ru') is-invalid @enderror"
                           id="name_ru" placeholder="Название RU" required>
                    @error('name.ru')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_en">Название EN</label>
                    <input type="text" name="name[en]" class="form-control @error('name.en') is-invalid @enderror"
                           id="name_en" placeholder="Название EN" required>
                    @error('name.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_uz">Название UZ</label>
                    <input type="text" name="name[uz]" class="form-control @error('name.uz') is-invalid @enderror"
                           id="name_uz" placeholder="Название UZ" required>
                    @error('name.uz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_kz">Название KZ</label>
                    <input type="text" name="name[kz]" class="form-control @error('name.kz') is-invalid @enderror"
                           id="name_kz" placeholder="Название KZ" required>
                    @error('name.kz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_tj">Название TJ</label>
                    <input type="text" name="name[tj]" class="form-control @error('name.tj') is-invalid @enderror"
                           id="name_tj" placeholder="Название TJ" required>
                    @error('name.tj')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_ru">Описание RU</label>
                    <textarea type="text" name="description[ru]"
                              class="form-control @error('description.ru') is-invalid @enderror" id="description_ru"
                              placeholder="Описание RU" required></textarea>
                    @error('description.ru')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_en">Описание EN</label>
                    <textarea type="text" name="description[en]"
                              class="form-control @error('description.en') is-invalid @enderror" id="description_en"
                              placeholder="Описание EN" required></textarea>
                    @error('description.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_uz">Описание UZ</label>
                    <textarea type="text" name="description[uz]"
                              class="form-control @error('description.uz') is-invalid @enderror" id="description_uz"
                              placeholder="Описание UZ" required></textarea>
                    @error('description.uz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_kz">Описание KZ</label>
                    <textarea type="text" name="description[kz]"
                              class="form-control @error('description.kz') is-invalid @enderror" id="description_kz"
                              placeholder="Описание KZ" required></textarea>
                    @error('description.kz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_tj">Описание TJ</label>
                    <textarea type="text" name="description[tj]"
                              class="form-control @error('description.tj') is-invalid @enderror" id="description_tj"
                              placeholder="Описание TJ" required></textarea>
                    @error('description.tj')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="working_hours">График работы</label>
                    <input type="text" name="working_hours"
                           class="form-control @error('working_hours') is-invalid @enderror" id="working_hours"
                           placeholder="График работы" required>
                    @error('working_hours')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="price_from">Цена от</label>
                    <input type="number" step="0.01" name="price_from"
                           class="form-control @error('price_from') is-invalid @enderror" id="price_from"
                           placeholder="Цена от">
                    @error('price_from')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="price_to">Цена до</label>
                    <input type="number" step="0.01" name="price_to"
                           class="form-control @error('price_to') is-invalid @enderror" id="price_to"
                           placeholder="Цена до">
                    @error('price_to')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="category_id">Категорие</label@extends('admin.layouts.app')

                    @section('title')
                        <title>Journal - Редактировать заведения</title>
                    @endsection

                    @section('content')
                        <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light"><a class="text-muted"
                                             href="{{ route('establishments.index') }}">Заведения</a> /</span>Редактировать
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
                                <form action="{{ route('establishments.update', $establishment->id) }}" method="POST"
                                      enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label class="form-label" for="name_ru">Название RU</label>
                                        <input type="text" name="name[ru]" class="form-control @error('name.ru') is-invalid @enderror"
                                               placeholder="Название RU"
                                               id="name_ru" value="{{ $establishment->name['ru'] }}" required>
                                        @error('name.ru')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="name_en">Название EN</label>
                                        <input type="text" name="name[en]" class="form-control @error('name.en') is-invalid @enderror"
                                               placeholder="Название EN"
                                               id="name_en" value="{{ $establishment->name['en'] }}" required>
                                        @error('name.en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="name_uz">Название UZ</label>
                                        <input type="text" name="name[uz]" class="form-control @error('name.uz') is-invalid @enderror"
                                               placeholder="Название UZ"
                                               id="name_uz" value="{{  $establishment->name['uz'] }}" required>
                                        @error('name.uz')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="name_kz">Название KZ</label>
                                        <input type="text" name="name[kz]" class="form-control @error('name.kz') is-invalid @enderror"
                                               placeholder="Название KZ"
                                               id="name_kz" value="{{ $establishment->name['kz'] }}" required>
                                        @error('name.kz')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="name_tj">Название TJ</label>
                                        <input type="text" name="name[tj]" class="form-control @error('name.tj') is-invalid @enderror"
                                               placeholder="Название TJ"
                                               id="name_tj" value="{{ $establishment->name['tj'] }}" required>
                                        @error('name.tj')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="description_ru">Описание RU</label>
                                        <textarea name="description[ru]"
                                                  class="form-control @error('description.ru') is-invalid @enderror" id="description_ru"
                                                  placeholder="Описание RU"
                                                  required>{{ $establishment->description['ru'] }}</textarea>
                                        @error('description.ru')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="description_en">Описание EN</label>
                                        <textarea name="description[en]"
                                                  class="form-control @error('description.en') is-invalid @enderror" id="description_en"
                                                  placeholder="Описание EN"
                                                  required>{{  $establishment->description['en'] }}</textarea>
                                        @error('description.en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="description_uz">Описание UZ</label>
                                        <textarea name="description[uz]"
                                                  class="form-control @error('description.uz') is-invalid @enderror" id="description_uz"
                                                  placeholder="Описание UZ"
                                                  required>{{ $establishment->description['uz'] }}</textarea>
                                        @error('description.uz')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="description_kz">Описание KZ</label>
                                        <textarea name="description[kz]"
                                                  class="form-control @error('description.kz') is-invalid @enderror" id="description_kz"
                                                  placeholder="Описание KZ"
                                                  required>{{ $establishment->description['kz'] }}</textarea>
                                        @error('description.kz')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="description_tj">Описание TJ</label>
                                        <textarea name="description[tj]"
                                                  class="form-control @error('description.tj') is-invalid @enderror" id="description_tj"
                                                  placeholder="Описание TJ"
                                                  required>{{ $establishment->description['tj'] }}</textarea>
                                        @error('description.tj')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="working_hours">График работы</label>
                                        <input type="text" name="working_hours"
                                               class="form-control @error('working_hours') is-invalid @enderror" id="working_hours"
                                               placeholder="График работы"
                                               value="{{ $establishment->working_hours }}" required>
                                        @error('working_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="price_from">Цена от</label>
                                        <input type="number" step="0.01" name="price_from"
                                               class="form-control @error('price_from') is-invalid @enderror" id="price_from"
                                               placeholder="Цена от"
                                               value="{{ round($establishment->price_from) }}">
                                        @error('price_from')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="price_to">Цена до</label>
                                        <input type="number" step="0.01" name="price_to"
                                               class="form-control @error('price_to') is-invalid @enderror" id="price_to"
                                               placeholder="Цена до"
                                               value="{{ round($establishment->price_to) }}">
                                        @error('price_to')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="category_id">Категорие</label>
                                        <select name="category_id" class="select2 form-control @error('category_id') is-invalid @enderror"
                                                id="category_id" required>
                                            <option value="">Выберите категорию</option>
                                            @foreach($categories as $category)
                                                <option
                                                    value="{{ $category->id }}" {{ $category->id == $establishment->category_id ? 'selected' : '' }}>
                                                    {{ $category->name['ru'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="location_link">Локация (ссылка)</label>
                                        <input type="text" name="location_link"
                                               class="form-control @error('location_link') is-invalid @enderror" id="location_link"
                                               value="{{$establishment->location_link}}"
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
                                        @if($establishment->images)
                                            @foreach(json_decode($establishment->images) as $photo)
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
                                            @foreach($establishment->contacts['type'] as $index => $contactType)
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
                                                                   value="{{ $establishment->contacts['type_value'][$index] }}" required>
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
                    >
                    <select name="category_id" class="select2 form-control @error('category_id') is-invalid @enderror"
                            id="category_id" required>
                        <option value="">Выберите категорию</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name['ru'] }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="location_link">Локация (ссылка)</label>
                    <input type="text" name="location_link"
                           class="form-control @error('location_link') is-invalid @enderror" id="location_link"
                           placeholder="Локация (ссылка)" required>
                    @error('location_link')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="imageInput" class="form-label">Загрузить фото</label>
                    <input type="file" name="photos[]" id="imageInput" class="form-control" multiple>
                </div>
                <div id="imagePreview" class="mb-3 main__td"></div>

                <div class="mb-3">
                    <label class="form-label">Контакты</label>
                    <div id="contacts-container">
                        <div class="contact-group mb-3 border p-3 rounded">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="contact-type-ru" class="form-label">Тип контакта (RU):</label>
                                    <input type="text" name="contacts[type][1][ru]"
                                           id="contact-type-ru"
                                           class="form-control @error('contacts.type.ru') is-invalid @enderror"
                                           placeholder="Напр. Телефон" required>
                                    @error('contacts.type.ru')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="contact-type-en" class="form-label">Тип контакта (EN):</label>
                                    <input type="text" name="contacts[type][1][en]"
                                           id="contact-type-en"
                                           class="form-control @error('contacts.type.en') is-invalid @enderror"
                                           placeholder="e.g., Phone" required>
                                    @error('contacts.type.en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="contact-type-uz" class="form-label">Тип контакта (UZ):</label>
                                    <input type="text" name="contacts[type][1][uz]"
                                           id="contact-type-uz"
                                           class="form-control @error('contacts.type.uz') is-invalid @enderror"
                                           placeholder="Напр. Телефон" required>
                                    @error('contacts.type.uz')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="contact-type-kz" class="form-label">Тип контакта (KZ):</label>
                                    <input type="text" name="contacts[type][1][kz]"
                                           id="contact-type-kz"
                                           class="form-control @error('contacts.type.kz') is-invalid @enderror"
                                           placeholder="e.g., Телефон" required>
                                    @error('contacts.type.kz')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="contact-type-tj" class="form-label">Тип контакта (TJ):</label>
                                    <input type="text" name="contacts[type][1][tj]"
                                           id="contact-type-tj"
                                           class="form-control @error('contacts.type.tj') is-invalid @enderror"
                                           placeholder="Напр. Телефон" required>
                                    @error('contacts.type.tj')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="contact-value" class="form-label">Значение контакта:</label>
                                    <input type="text" name="contacts[type_value][1]"
                                           id="contact-value"
                                           class="form-control @error('contacts.type_value') is-invalid @enderror"
                                           placeholder="e.g., 998900000000" required>
                                    @error('contacts.type_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-secondary mt-3" id="add-contact">Добавить Контакт</button>
                </div>

                <button type="submit" class="btn btn-primary">Сохранить</button>
            </form>
        </div>
    </div>
@endsection

