@php
$menuList = [
    [
        'label' => 'ui.nav.home',
        'url' => route('home'),
        'image' => 'home-outline',
        'guard' => '',
    ],
    [
        'label' => 'ui.nav.dashboard',
        'url' => route('dashboard'),
        'image' => 'pie-chart-outline',
        'guard' => '',
    ],
    [
        'label' => 'ui.nav.add_widget',
        'url' => route('dashboard.widget'),
        'image' => 'albums-outline',
        'guard' => '',
    ],
    [
        'label' => 'ui.nav.widgets',
        'url' => route('widget'),
        'image' => 'apps-outline',
        'guard' => 'admin',
    ],
    [
        'label' => 'ui.nav.create_widget',
        'url' => route('widget.create'),
        'image' => 'add-circle-outline',
        'guard' => 'admin',
    ],
    [
        'label' => 'ui.nav.devices',
        'url' => route('devices'),
        'image' => 'hardware-chip-outline',
        'guard' => '',
    ],
    [
        'label' => 'ui.nav.add_device',
        'url' => route('devices.create'),
        'image' => 'add-circle-outline',
        'guard' => '',
    ],
    [
        'label' => 'ui.nav.scenarios',
        'url' => route('scenario'),
        'image' => 'construct-outline',
        'guard' => '',
    ],
    [
        'label' => 'ui.nav.add_scenario',
        'url' => route('scenario.create'),
        'image' => 'add-circle-outline',
        'guard' => '',
    ],
    [
        'label' => 'ui.nav.voice_assistant',
        'url' => route('voice'),
        'image' => 'mic-outline',
        'guard' => '',
    ],
    [
        'label' => 'ui.nav.create_voice_command',
        'url' => route('voice.create'),
        'image' => 'add-circle-outline',
        'guard' => '',
    ],
    [
        'label' => 'ui.nav.apps',
        'url' => route('apps.index'),
        'image' => 'extension-puzzle-outline',
        'guard' => 'admin',
    ],
    [
        'label' => 'ui.nav.install_app',
        'url' => route('apps.upload'),
        'image' => 'cloud-upload-outline',
        'guard' => 'admin',
    ],
    [
        'label' => 'ui.nav.accounts',
        'url' => route('accounts.index'),
        'image' => 'people-outline',
        'guard' => 'admin',
    ],
    [
        'label' => 'ui.nav.settings',
        'url' => route('profile'),
        'image' => 'settings-outline',
        'guard' => '',
    ],
    [
        'label' => 'ui.nav.logout',
        'url' => route('logout'),
        'image' => 'log-out-outline',
        'guard' => '',
    ],
];

$appMenuList = [];
try {
    $appMenuList = app(\App\Services\AppManager::class)->getMenuItems();
} catch (\Throwable $e) {
    $appMenuList = [];
}

$currentTheme = session('theme', 'light');
if (!in_array($currentTheme, ['light', 'dark'], true)) {
    $currentTheme = 'light';
}

$currentLocale = app()->getLocale();

function MenuGuard(string $GuardString): bool
{
    if (empty($GuardString)) {
        return true;
    }

    $guards = explode('|', $GuardString);
    foreach ($guards as $guard) {
        if ($guard === \Illuminate\Support\Facades\Auth::user()->role) {
            return true;
        }
    }

    return false;
}
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v={{ filemtime(public_path('favicon.svg')) }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ filemtime(public_path('favicon.ico')) }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v={{ filemtime(public_path('favicon.ico')) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}?v={{ filemtime(public_path('css/menu.css')) }}">

    <title>@yield('title', __('ui.layout.workspace'))</title>
    @yield('styles', '')

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<body class="app-shell" data-theme="{{ $currentTheme }}">

    <nav id="navbar" aria-label="Main navigation">
        <div class="sidebar-brand">
            <div class="brand-mark">PA</div>
            <div class="brand-copy">
                <strong>{{ __('ui.layout.brand') }}</strong>
                <small>{{ __('ui.layout.control_hub') }}</small>
            </div>
        </div>

        <div class="sidebar-user">
            <ion-icon name="person-circle-outline"></ion-icon>
            <span>{{ Auth::user()->name }}</span>
        </div>

        <ul class="navbar-items flexbox-col">
            @foreach ($menuList as $menu)
                @if (MenuGuard($menu['guard']))
                    <x-menu-item name="{{ __($menu['label']) }}" href="{{ $menu['url'] }}" icon="{{ $menu['image'] }}"></x-menu-item>
                @endif
            @endforeach

            @foreach ($appMenuList as $menu)
                @php
                    $menuUrl = $menu['url'] ?? null;
                    if (!$menuUrl && !empty($menu['route'])) {
                        try {
                            $menuUrl = route($menu['route'], $menu['route_params'] ?? []);
                        } catch (\Throwable $e) {
                            $menuUrl = null;
                        }
                    }
                @endphp
                @if (MenuGuard($menu['guard']) && $menuUrl)
                    <x-menu-item name="{{ __($menu['label']) }}" href="{{ $menuUrl }}" icon="{{ $menu['image'] }}"></x-menu-item>
                @endif
            @endforeach
        </ul>
    </nav>

    <main id="main" class="container-fluid">
        <header class="app-topbar">
            <div class="app-topbar-title-wrap">
                <p class="app-kicker">{{ __('ui.layout.workspace') }}</p>
                <h1 class="app-title">@yield('title', __('ui.layout.workspace'))</h1>
            </div>

            <div class="app-topbar-actions">
                <div class="app-pref-switcher">
                    <span class="app-pref-label">{{ __('ui.common.language') }}</span>
                    <a href="{{ route('preferences.locale', 'ru') }}" class="pref-chip @if ($currentLocale === 'ru') is-active @endif">RU</a>
                    <a href="{{ route('preferences.locale', 'en') }}" class="pref-chip @if ($currentLocale === 'en') is-active @endif">EN</a>
                </div>

                <div class="app-pref-switcher">
                    <span class="app-pref-label">{{ __('ui.common.theme') }}</span>
                    <a href="{{ route('preferences.theme', 'light') }}" class="pref-chip @if ($currentTheme === 'light') is-active @endif">{{ __('ui.common.light') }}</a>
                    <a href="{{ route('preferences.theme', 'dark') }}" class="pref-chip @if ($currentTheme === 'dark') is-active @endif">{{ __('ui.common.dark') }}</a>
                </div>

                <div class="app-topbar-user">
                    <ion-icon name="sparkles-outline"></ion-icon>
                    <span>{{ Auth::user()->name }}</span>
                </div>
            </div>
        </header>

        @if (session('success'))
            <div class="alert alert-success ion-fade-in mb-3">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger ion-fade-in mb-3">
                {{ session('error') }}
            </div>
        @endif

        <section class="app-content">
            @yield('content')
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
