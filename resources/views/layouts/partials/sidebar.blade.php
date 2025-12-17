@php
    $colors = [
        'text-primary-600',
        'text-danger-600',
        'text-success-600',
        'text-warning-600',
        'text-info-600',
        'text-purple-600',
        'text-secondary-600',
    ];
@endphp

<button type="button"
    class="theme-customization__button w-48-px h-48-px bg-primary-600 text-white rounded-circle d-flex justify-content-center align-items-center position-fixed end-0 bottom-0 mb-40 me-40 text-2xxl bg-hover-primary-700">
    <i class="ri-settings-3-line animate-spin"></i>
</button>
<div class="theme-customization-sidebar w-100 bg-base h-100vh overflow-y-auto position-fixed end-0 top-0 shadow-lg">
    <div class="d-flex align-items-center gap-3 py-16 px-24 justify-content-between border-bottom">
        <div>
            <h6 class="text-sm dark:text-white">Theme Settings</h6>
            <p class="text-xs mb-0 text-neutral-500 dark:text-neutral-200">Customize and preview instantly</p>
        </div>
        <button data-slot="button"
            class="theme-customization-sidebar__close text-neutral-900 bg-transparent text-hover-primary-600 d-flex text-xl">
            <i class="ri-close-fill"></i>
        </button>
    </div>

    <div class="d-flex flex-column gap-48 p-24 overflow-y-auto flex-grow-1">

        <div class="theme-setting-item">
            <h6 class="fw-medium text-primary-light text-md mb-3">Theme Mode</h6>
            <div class="d-grid grid-cols-3 gap-3 dark-light-mode">
                <button type="button"
                    class="theme-btn theme-setting-item__btn d-flex align-items-center justify-content-center h-64-px rounded-3 text-xl active"
                    data-theme="light">
                    <i class="ri-sun-line"></i>
                </button>
                <button type="button"
                    class="theme-btn theme-setting-item__btn d-flex align-items-center justify-content-center h-64-px rounded-3 text-xl"
                    data-theme="dark">
                    <i class="ri-moon-line"></i>
                </button>
                <button type="button"
                    class="theme-btn theme-setting-item__btn d-flex align-items-center justify-content-center h-64-px rounded-3 text-xl"
                    data-theme="system">
                    <i class="ri-computer-line"></i>
                </button>
            </div>
        </div>

        <div class="theme-setting-item">
            <h6 class="fw-medium text-primary-light text-md mb-3">Page Direction</h6>
            <div class="d-grid grid-cols-2 gap-3">
                <button type="button"
                    class="theme-setting-item__btn ltr-mode-btn d-flex align-items-center justify-content-center gap-2 h-56-px rounded-3 text-xl">
                    <span><i class="ri-align-item-left-line"></i></span>
                    <span class="h6 text-sm font-medium mb-0">LTR</span>
                </button>

                <button type="button"
                    class="theme-setting-item__btn rtl-mode-btn d-flex align-items-center justify-content-center gap-2 h-56-px rounded-3 text-xl">
                    <span class="h6 text-sm font-medium mb-0">RTL</span>
                    <span><i class="ri-align-item-right-line"></i></span>
                </button>
            </div>
        </div>

        <div class="theme-setting-item">
            <h6 class="fw-medium text-primary-light text-md mb-3">Color Schema</h6>
            <div class="d-grid grid-cols-3 gap-3">
                <button type="button"
                    class="color-picker-btn d-flex flex-column justify-content-center align-items-center"
                    data-color="blue">
                    <span class="color-picker-btn__box h-40-px w-100 rounded-3"
                        style="background-color: #2563eb;"></span>
                    <span class="fw-medium mt-1" style="color: #2563eb;">Blue</span>
                </button>
                <button type="button"
                    class="color-picker-btn d-flex flex-column justify-content-center align-items-center"
                    data-color="red">
                    <span class="color-picker-btn__box h-40-px w-100 rounded-3"
                        style="background-color: #dc2626;"></span>
                    <span class="fw-medium mt-1" style="color: #dc2626;">Red</span>
                </button>
                <button type="button"
                    class="color-picker-btn d-flex flex-column justify-content-center align-items-center"
                    data-color="green">
                    <span class="color-picker-btn__box h-40-px w-100 rounded-3"
                        style="background-color: #16a34a;"></span>
                    <span class="fw-medium mt-1" style="color: #16a34a;">Green</span>
                </button>
                <button type="button"
                    class="color-picker-btn d-flex flex-column justify-content-center align-items-center"
                    data-color="yellow">
                    <span class="color-picker-btn__box h-40-px w-100 rounded-3"
                        style="background-color: #ff9f29;"></span>
                    <span class="fw-medium mt-1" style="color: #ff9f29;">Yellow</span>
                </button>
                <button type="button"
                    class="color-picker-btn d-flex flex-column justify-content-center align-items-center"
                    data-color="cyan">
                    <span class="color-picker-btn__box h-40-px w-100 rounded-3"
                        style="background-color: #00b8f2;"></span>
                    <span class="fw-medium mt-1" style="color: #00b8f2;">Cyan</span>
                </button>
                <button type="button"
                    class="color-picker-btn d-flex flex-column justify-content-center align-items-center"
                    data-color="violet">
                    <span class="color-picker-btn__box h-40-px w-100 rounded-3"
                        style="background-color: #7c3aed;"></span>
                    <span class="fw-medium mt-1" style="color: #7c3aed;">Violet</span>
                </button>
            </div>
        </div>

    </div>
</div>
<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ route('dashboard') }}" class="sidebar-logo">
            <img src="{{ asset('assets/backend/logo_landscape.png') }}" alt="site logo" class="light-logo">
            <img src="{{ asset('assets/backend/logo_landscape_white.png') }}" alt="site logo" class="dark-logo">
            <img src="{{ asset('assets/backend/logosaja.png') }}" alt="site logo" class="logo-icon">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-menu-group-title">Users</li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Users</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('users.list') }}"><i
                                class="ri-circle-fill circle-icon {{ Arr::random($colors) }} w-auto"></i>
                            Users Internal</a>
                    </li>
                    <li>
                        <a href="{{ route('usersEksternal.list') }}"><i
                                class="ri-circle-fill circle-icon {{ Arr::random($colors) }} w-auto"></i>
                            Users Eksternal</a>
                    </li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <i class="ri-user-settings-line text-xl me-14 d-flex w-auto"></i>
                    <span>Role & Permission</span>
                </a>
                <ul class="sidebar-submenu">
                    <li>
                        <a href="{{ route('roles.list') }}"><i
                                class="ri-circle-fill circle-icon {{ Arr::random($colors) }} w-auto"></i>
                            Role</a>
                    </li>
                    <li>
                        <a href="{{ route('permissions.list') }}"><i
                                class="ri-circle-fill circle-icon {{ Arr::random($colors) }} w-auto"></i>
                            Permission</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</aside>
