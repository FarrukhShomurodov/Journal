<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <h3 style="margin: 0px !important;">
            <a href="{{ route('dashboard') }}" class="app-brand-link">
                Journal
            </a>
        </h3>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx menu-toggle-icon d-none d-xl-block fs-4 align-middle"></i>
            <i class="bx bx-x d-block d-xl-none bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>

    <ul class="menu-inner py-1 ps ps--active-y">
        <li class="menu-item {{ Request::is('/') || Request::is('statistics*') ? 'active' : '' }}">
            <a href="{{route('dashboard')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Панели управления">Панели управления</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('bot-users*') ||  Request::is('bot-user*')? 'active' : '' }}">
            <a href="{{route('bot.users')}}" class="menu-link">
                <i class='menu-icon bx bx-bot'></i>
                <div data-i18n="Пользователи бота">Пользователи бота</div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('countries*') || Request::is('countries*') || Request::is('cities*') ? 'open' : '' }}"
            style="">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-globe"></i>
                <div data-i18n="Местоположения">Местоположения</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('/countries') || Request::is('countries*') ? 'active' : '' }}">
                    <a href="{{route('countries.index')}}" class="menu-link">
                        <div data-i18n="Страны">Страны</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('/cities') || Request::is('cities*') ? 'active' : '' }}">
                    <a href="{{route('cities.index')}}" class="menu-link">
                        <div data-i18n="Города">Города</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ Request::is('applications*') ? 'active' : '' }}">
            <a href="{{route('applications')}}" class="menu-link">
                <i class='menu-icon bx bx-edit'></i>
                <div data-i18n="Заявки">Заявки</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('/currencies') || Request::is('currencies*') ? 'active' : '' }}">
            <a href="{{route('currencies.index')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-money"></i>
                <div data-i18n="Валюты">Валюты</div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('establishments*') || Request::is('categories*') ? 'open' : '' }}" style="">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-building"></i>
                <div data-i18n="Заведения">Заведения</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('/establishments') || Request::is('establishments*') ? 'active' : '' }}">
                    <a href="{{route('establishments.index')}}" class="menu-link">

                        <div data-i18n="Заведение">Список</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('/categories') || Request::is('categories*') ? 'active' : '' }}">
                    <a href="{{route('categories.index')}}" class="menu-link">
                        <div data-i18n="Категории">Категории</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ Request::is('/entertainments') || Request::is('entertainments*') ? 'active' : '' }}">
            <a href="{{route('entertainments.index')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-party"></i>
                <div data-i18n="Развлечения">Развлечения</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('/hotels') || Request::is('hotels*') ? 'active' : '' }}">
            <a href="{{route('hotels.index')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-hotel"></i>
                <div data-i18n="Отели">Отели</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('/usefulInfos') || Request::is('usefulInfos*') ? 'active' : '' }}">
            <a href="{{route('usefulInfos.index')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-info-circle"></i>
                <div data-i18n="Полезная информация">Полезная информация</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('/promotions') || Request::is('promotions*') ? 'active' : '' }}">
            <a href="{{route('promotions.index')}}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-gift"></i>
                <div data-i18n="Акции">Акции</div>
            </a>
        </li>

        <li class="menu-item {{ Request::is('clinics*') || Request::is('diseaseTypes*') || Request::is('specializations*') ? 'open' : '' }}"
            style="">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-health"></i>
                <div data-i18n="Клиники">Клиники</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Request::is('/clinics') || Request::is('clinics*') ? 'active' : '' }}">
                    <a href="{{route('clinics.index')}}" class="menu-link">
                        <div data-i18n="Клиники">Список</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('/diseaseTypes') || Request::is('diseaseTypes*') ? 'active' : '' }}">
                    <a href="{{route('diseaseTypes.index')}}" class="menu-link">
                        <div data-i18n="Типы заболеваний">Типы заболеваний</div>
                    </a>
                </li>
                <li class="menu-item {{ Request::is('/specializations') || Request::is('specializations*') ? 'active' : '' }}">
                    <a href="{{route('specializations.index')}}" class="menu-link">
                        <div data-i18n="Специализации">Специализации</div>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</aside>

<div class="layout-overlay layout-menu-toggle"></div>
