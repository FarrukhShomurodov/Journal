@extends('admin.layouts.app')

@section('title')
    <title>{{ 'Findz - ' . __('entertainment.edit_entertainment') }}</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light"><a class="text-muted"
                                             href="{{ route('entertainments.index') }}">{{ __('entertainment.entertainments') }}</a> /</span>@lang('commands.edit')
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
            <h5 class="mb-0">@lang('commands.edit')</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('entertainments.update', $entertainment->id) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- Используем метод PUT для обновления --}}

                {{-- Поле для названия на разных языках --}}
                <div class="mb-3">
                    <label class="form-label" for="name_ru">@lang('entertainment.name') RU</label>
                    <input type="text" name="name[ru]" class="form-control @error('name.ru') is-invalid @enderror"
                           id="name_ru" value="{{ old('name.ru', $entertainment->name['ru']) }}" required>
                    @error('name.ru')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_en">@lang('entertainment.name') EN</label>
                    <input type="text" name="name[en]" class="form-control @error('name.en') is-invalid @enderror"
                           id="name_en" value="{{ old('name.en', $entertainment->name['en']) }}" required>
                    @error('name.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_uz">@lang('entertainment.name') UZ</label>
                    <input type="text" name="name[uz]" class="form-control @error('name.uz') is-invalid @enderror"
                           id="name_uz" value="{{ old('name.uz', $entertainment->name['uz']) }}" required>
                    @error('name.uz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_kz">@lang('entertainment.name') KZ</label>
                    <input type="text" name="name[kz]" class="form-control @error('name.kz') is-invalid @enderror"
                           id="name_kz" value="{{ old('name.kz', $entertainment->name['kz']) }}" required>
                    @error('name.kz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_tj">@lang('entertainment.name') TJ</label>
                    <input type="text" name="name[tj]" class="form-control @error('name.tj') is-invalid @enderror"
                           id="name_tj" value="{{ old('name.tj', $entertainment->name['tj']) }}" required>
                    @error('name.tj')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Поле для описания на разных языках --}}
                <div class="mb-3">
                    <label class="form-label" for="description_ru">@lang('entertainment.description') RU</label>
                    <textarea name="description[ru]"
                              class="form-control @error('description.ru') is-invalid @enderror" id="description_ru"
                              required>{{ old('description.ru', $entertainment->description['ru']) }}</textarea>
                    @error('description.ru')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_en">@lang('entertainment.description') EN</label>
                    <textarea name="description[en]"
                              class="form-control @error('description.en') is-invalid @enderror" id="description_en"
                              required>{{ old('description.en', $entertainment->description['en']) }}</textarea>
                    @error('description.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_uz">@lang('entertainment.description') UZ</label>
                    <textarea name="description[uz]"
                              class="form-control @error('description.uz') is-invalid @enderror" id="description_uz"
                              required>{{ old('description.uz', $entertainment->description['uz']) }}</textarea>
                    @error('description.uz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_kz">@lang('entertainment.description') KZ</label>
                    <textarea name="description[kz]"
                              class="form-control @error('description.kz') is-invalid @enderror" id="description_kz"
                              required>{{ old('description.kz', $entertainment->description['kz']) }}</textarea>
                    @error('description.kz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_tj">@lang('entertainment.description') TJ</label>
                    <textarea name="description[tj]"
                              class="form-control @error('description.tj') is-invalid @enderror" id="description_tj"
                              required>{{ old('description.tj', $entertainment->description['tj']) }}</textarea>
                    @error('description.tj')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Рабочие часы, цены, категория и ссылки --}}
                <div class="mb-3">
                    <label class="form-label" for="working_hours">@lang('entertainment.working_hours')</label>
                    <input type="text" name="working_hours"
                           class="form-control @error('working_hours') is-invalid @enderror" id="working_hours"
                           value="{{ old('working_hours', $entertainment->working_hours) }}">
                    @error('working_hours')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="price_from">@lang('entertainment.price_from')</label>
                    <input type="number" step="0.01" name="price_from"
                           class="form-control @error('price_from') is-invalid @enderror" id="price_from"
                           value="{{ old('price_from', $entertainment->price_from) }}">
                    @error('price_from')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="price_to">@lang('entertainment.price_to')</label>
                    <input type="number" step="0.01" name="price_to"
                           class="form-control @error('price_to') is-invalid @enderror" id="price_to"
                           value="{{ old('price_to', $entertainment->price_to) }}">
                    @error('price_to')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="location_link">@lang('entertainment.location_link')</label>
                    <input type="text" name="location_link"
                           class="form-control @error('location_link') is-invalid @enderror" id="location_link"
                           value="{{$entertainment->location_link}}"
                           placeholder="@lang('entertainment.location_link')">
                    @error('location_link')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="imageInput" class="form-label">{{ __('entertainment.upload_images') }}</label>
                    <input type="file" name="photos[]" id="imageInput" class="form-control" multiple>
                </div>
                <div id="imagePreview" class="mb-3 main__td">
                    @if($entertainment->images)
                        @foreach(json_decode($entertainment->images) as $photo)
                            <div class="image-container td__img" data-photo-path="{{ $photo->url }}">
                                <img src="{{ asset('storage/' . $photo->url) }}" alt="Court Image"
                                     class="uploaded-image">
                                <button type="button" class="btn btn-danger btn-sm delete-image"
                                        data-photo-path="{{ $photo->url }}"> {{ __('court.delete') }}
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">@lang('entertainment.contacts')</label>
                    <div id="contacts-container">
                        @foreach($entertainment->contacts['type'] as $index => $contactType)
                            <div class="contact-group mb-2">
                                <div class="d-flex">
                                    <div class="me-2">
                                        <label>@lang('entertainment.contact_type'):</label>
                                        <input type="text" name="contacts[type][{{ $index }}]"
                                               class="form-control @error('contacts.type.' . $index) is-invalid @enderror"
                                               placeholder="(e.g., Phone)"
                                               value="{{ $contactType }}">
                                        @error('contacts.type.' . $index)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <label>@lang('entertainment.contact_value'):</label>
                                        <input type="text" name="contacts[type_value][{{ $index }}]"
                                               class="form-control @error('contacts.type_value.' . $index) is-invalid @enderror"
                                               placeholder="(e.g., 99890000000)"
                                               value="{{ $entertainment->contacts['type_value'][$index] }}">
                                        @error('contacts.type_value.' . $index)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @if($index > 1)
                                        <button type="button"
                                                class="btn btn-danger mt-3 ms-2 delete-contact">@lang("entertainment.delete_contact")</button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-secondary mt-2"
                            id="add-contact">@lang('entertainment.add_contact')</button>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-warning">@lang('commands.edit')</button>
                </div>
            </form>
        </div>
    </div>
@endsection
