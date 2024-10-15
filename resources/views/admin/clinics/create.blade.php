@extends('admin.layouts.app')

@section('title')
    <title>{{ 'Findz - ' . __('clinic.create_clinic') }}</title>
@endsection

@section('content')
    <h6 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light"><a class="text-muted"
                                             href="{{ route('clinics.index') }}">{{ __('clinic.clinics') }}</a> /</span>@lang('commands.create')
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
            <h5 class="mb-0">@lang('commands.create')</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('clinics.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="name_ru">@lang('clinic.name') RU</label>
                    <input type="text" name="name[ru]" class="form-control @error('name.ru') is-invalid @enderror"
                           id="name_ru" placeholder="@lang('clinic.name')" required>
                    @error('name.ru')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_en">@lang('clinic.name') EN</label>
                    <input type="text" name="name[en]" class="form-control @error('name.en') is-invalid @enderror"
                           id="name_en" placeholder="@lang('clinic.name')" required>
                    @error('name.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_uz">@lang('clinic.name') UZ</label>
                    <input type="text" name="name[uz]" class="form-control @error('name.uz') is-invalid @enderror"
                           id="name_uz" placeholder="@lang('clinic.name')" required>
                    @error('name.uz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_kz">@lang('clinic.name') KZ</label>
                    <input type="text" name="name[kz]" class="form-control @error('name.kz') is-invalid @enderror"
                           id="name_kz" placeholder="@lang('clinic.name')" required>
                    @error('name.kz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="name_tj">@lang('clinic.name') TJ</label>
                    <input type="text" name="name[tj]" class="form-control @error('name.tj') is-invalid @enderror"
                           id="name_tj" placeholder="@lang('clinic.name')" required>
                    @error('name.tj')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_ru">@lang('clinic.description') RU</label>
                    <textarea type="text" name="description[ru]"
                              class="form-control @error('description.ru') is-invalid @enderror" id="description_ru"
                              placeholder="@lang('clinic.description')" required></textarea>
                    @error('description.ru')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_en">@lang('clinic.description') EN</label>
                    <textarea type="text" name="description[en]"
                              class="form-control @error('description.en') is-invalid @enderror" id="description_en"
                              placeholder="@lang('clinic.description')" required></textarea>
                    @error('description.en')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_uz">@lang('clinic.description') UZ</label>
                    <textarea type="text" name="description[uz]"
                              class="form-control @error('description.uz') is-invalid @enderror" id="description_uz"
                              placeholder="@lang('clinic.description')" required></textarea>
                    @error('description.uz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_kz">@lang('clinic.description') KZ</label>
                    <textarea type="text" name="description[kz]"
                              class="form-control @error('description.kz') is-invalid @enderror" id="description_kz"
                              placeholder="@lang('clinic.description')" required></textarea>
                    @error('description.kz')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description_tj">@lang('clinic.description') TJ</label>
                    <textarea type="text" name="description[tj]"
                              class="form-control @error('description.tj') is-invalid @enderror" id="description_tj"
                              placeholder="@lang('clinic.description')" required></textarea>
                    @error('description.tj')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="working_hours">@lang('clinic.working_hours')</label>
                    <input type="text" name="working_hours"
                           class="form-control @error('working_hours') is-invalid @enderror" id="working_hours"
                           placeholder="@lang('clinic.working_hours')">
                    @error('working_hours')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="location_link">@lang('clinic.location_link')</label>
                    <input type="text" name="location_link"
                           class="form-control @error('location_link') is-invalid @enderror" id="location_link"
                           placeholder="@lang('clinic.location_link')">
                    @error('location_link')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="rating">Рейтинг</label>
                    <input type="number" name="rating"
                           class="form-control @error('rating') is-invalid @enderror" id="rating"
                           placeholder="@lang('clinic.rating')"
                           min="1" max="5" step="0.1" required>
                    @error('rating')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="category_id">Спецелизация</label>
                    <select name="specialization[]" class="form-control @error('category_id') is-invalid @enderror"
                            id="category_id" required multiple>
                        <option value="">@lang('establishment.select_category')</option>
                        @foreach($specializations as $specialization)
                            <option value="{{ $specialization->id }}">{{ $specialization->name['ru'] }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="category_id">Тип болезни</label>
                    <select name="disease_type[]" class="form-control @error('category_id') is-invalid @enderror"
                            id="category_id" required multiple>
                        <option value="">@lang('establishment.select_category')</option>
                        @foreach($diseaseTypes as $diseaseType)
                            <option value="{{ $diseaseType->id }}">{{ $diseaseType->name['ru'] }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="imageInput" class="form-label">{{ __('clinic.upload_images') }}</label>
                    <input type="file" name="photos[]" id="imageInput" class="form-control" multiple>
                </div>
                <div id="imagePreview" class="mb-3 main__td"></div>

                <div class="mb-3">
                    <label class="form-label">@lang('clinic.contacts')</label>
                    <div id="contacts-container">
                        <div class="contact-group mb-2">
                            <div class="d-flex">
                                <div class="me-2">
                                    <label>@lang('clinic.contact_type'):</label>
                                    <input type="text" name="contacts[type][1]"
                                           class="form-control @error('contacts.phone') is-invalid @enderror"
                                           placeholder="(e.g., Phone)">
                                    @error('contacts.type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label>@lang('clinic.contact_value'):</label>
                                    <input type="text" name="contacts[type_value][1]"
                                           class="form-control @error('contacts.phone_value') is-invalid @enderror"
                                           placeholder="(e.g., 99890000000)">
                                    @error('contacts.type_value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary mt-2"
                            id="add-contact">@lang('clinic.add_contact')</button>
                </div>

                <button type="submit" class="btn btn-primary">@lang('commands.save')</button>
            </form>
        </div>
    </div>
@endsection
