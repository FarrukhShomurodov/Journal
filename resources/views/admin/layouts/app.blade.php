<!DOCTYPE html>
<!-- beautify ignore:start -->
<html lang="en" class="light-style layout-menu-fixed " dir="ltr" data-theme="theme-default"
      data-assets-path="/" data-template="horizontal-menu-template">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>

    @yield('title')

    <meta name="description" content="Start your development with a Dashboard for Bootstrap 5"/>
    <meta name="keywords"
          content="dashboard, bootstrap 5 dashboard, bootstrap 5 admin, bootstrap 5 design, bootstrap 5">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}"/>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('vendor/fonts/boxicons.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/fonts/fontawesome.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/fonts/flag-icons.css') }}"/>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/css/rtl/core.css') }}" class="template-customizer-core-css"/>
    <link rel="stylesheet" href="{{ asset('vendor/css/rtl/theme-default.css') }}"
          class="template-customizer-theme-css"/>
    <link rel="stylesheet" href="{{ asset('css/demo.css') }}"/>

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{asset('vendor/libs/fullcalendar/fullcalendar.css')}}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/libs/typeahead-js/typeahead.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/libs/apex-charts/apex-charts.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/libs/bootstrap-select/bootstrap-select.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/libs/tagify/tagify.css') }}"/>
    <link rel="stylesheet" href="{{ asset('vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/libs/dropzone/dropzone.css') }}"/>
    <link rel="stylesheet" href="{{asset('vendor/libs/select2/select2.css')}}"/>
    <link rel="stylesheet" href="{{asset('vendor/libs/quill/editor.css')}}" />
    <link rel="stylesheet" href="{{asset('vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{asset('vendor/css/pages/app-calendar.css')}}" />

    <!-- Helpers -->
    <script src="{{ asset('vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('js/config.js') }}"></script>
    <style>
        body{
            overflow-x: hidden;
        }
    </style>
</head>

<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar  ">
    <div class="layout-container">

        @include('admin.layouts.menu')

        <!-- Layout container -->
        <div class="layout-page">

            @include('admin.layouts.header')

            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Core JS -->
<!-- build:js vendor/js/core.js -->
<script src="{{ asset('vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
<script src="{{ asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<script src="{{ asset('vendor/libs/hammer/hammer.js') }}"></script>


<script src="{{ asset('vendor/libs/i18n/i18n.js') }}"></script>
<script src="{{ asset('vendor/libs/typeahead-js/typeahead.js') }}"></script>

<script src="{{ asset('vendor/js/menu.js') }}"></script>
<!-- end build -->

<!-- Vendors JS -->
<script src="{{ asset('vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="{{asset('vendor/libs/select2/select2.js')}}"></script>


<!-- Vendors JS -->
<script src="{{{asset('vendor/libs/fullcalendar/fullcalendar.js')}}}"></script>
<script src="{{asset('vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
<script src="{{asset('vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('vendor/libs/moment/moment.js')}}"></script>

<!-- Main JS -->
<script src="{{asset('js/forms-selects.js')}}"></script>

<script src="{{ asset('js/main.js') }}"></script>

<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/ru.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

@yield('scripts')
<script>
    $(document).ready(function() {
        setTimeout(function() {
            $('.alert').fadeOut('slow', function() {
                $(this).remove();
            });
        }, 2000);

        $('#imageInput').on('change', function () {
            const files = Array.from($(this)[0].files);
            const imagePreview = $('#imagePreview');

            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const imgElement = $('<img>', {
                        src: e.target.result,
                        alt: file.name,
                        class: 'uploaded-image'
                    });

                    const imgContainer = $('<div>', {class: 'image-container td__img'});
                    imgContainer.append(imgElement);

                    const deleteBtn = $('<button>', {
                        class: 'btn btn-danger btn-sm delete-image',
                        text: 'Удалить',
                        click: function () {
                            imgContainer.remove();
                            const index = files.indexOf(file);
                            if (index !== -1) {
                                files.splice(index, 1);
                                updateFileInput(files);
                            }
                        }
                    });
                    imgContainer.append(deleteBtn);

                    imagePreview.append(imgContainer);
                };
                reader.readAsDataURL(file);
            });
        });

        function updateFileInput(files) {
            const input = $('#imageInput')[0];
            const fileList = new DataTransfer();
            files.forEach(file => {
                fileList.items.add(file);
            });
            input.files = fileList.files;
        }

        $(document).on('click', '.delete-image', function () {
            const path = $(this).data('photo-path');
            if (path) {
                $.ajax({
                    url: `/api/delete/image/${path}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (res) {
                        console.log(res);
                        $(this).closest('.image-container').remove();
                    }.bind(this),
                    error: function (error) {
                        console.error('Error deleting photo:', error);
                    }
                });
            }
        });

        @php
            $i = 1;

            if (isset($entertainment)) {
                $i = count($entertainment->contacts['type']);
            } elseif (isset($establishment)) {
                $i = count($establishment->contacts['type']);
            } elseif (isset($hotel)) {
                $i = count($hotel->contacts['type']);
            }elseif (isset($clinic)) {
                $i = count($clinic->contacts['type']);
            }
        @endphp

        let i = {{ $i }};

        $('#add-contact').click(function () {
            i++;
            const contactGroup = $('<div class="contact-group mb-3 border p-3 rounded"></div>');
            const flexContainer = $('<div class="row g-3 align-items-center"></div>');

            const languages = ['ru', 'en', 'uz', 'kz', 'tj'];
            languages.forEach(lang => {
                const contactTypeInput = $('<div class="col-md-6"></div>').append(
                    $('<label></label>').text(`Тип контакта (${lang.toUpperCase()}):`),
                    $('<input>', {
                        type: 'text',
                        name: `contacts[type][${i}][${lang}]`,
                        class: 'form-control',
                        placeholder: `Напр. Телефон (${lang.toUpperCase()})`,
                        required: true
                    })
                );
                flexContainer.append(contactTypeInput);
            });


            const contactValueInput = $('<div class="col-md-6"></div>').append(
                $('<label>Значение контакта:</label>'),
                $('<input>', {
                    type: 'text',
                    name: `contacts[type_value][${i}]`,
                    class: 'form-control',
                    placeholder: 'e.g., 998900000000',
                    required: true
                })
            );

            flexContainer.append(contactValueInput);


            const deleteButton = $('<div class="col-md-12 text-end"></div>').append(
                $('<button type="button" class="btn btn-danger delete-contact">Удалить контакт</button>')
            );


            flexContainer.append(deleteButton);
            contactGroup.append(flexContainer);

            $('#contacts-container').append(contactGroup);
        });


        $(document).on('click', '.delete-contact', function () {
            $(this).closest('.contact-group').remove();
        });

        $('.select2').select2();
    });
</script>
</body>

</html>
