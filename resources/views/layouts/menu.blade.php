@php
   $menuList = [
      [
            'name'=>'Головна',
            'url'=>route('home'),
            'image'=>'home-outline',
            'guard'=>''
       ],[
            'name'=>'Головна дошка',
            'url'=>route('dashboard'),
            'image'=>'pie-chart-outline',
            'guard'=>''
       ],[
            'name'=>'Додати віджет',
            'url'=>route('dashboard.widget'),
            'image'=>'albums-outline',
            'guard'=>''
       ],[
            'name'=>'Усі віджети',
            'url'=>route('widget'),
            'image'=>'apps-outline',
            'guard'=>'admin'
       ],[
            'name'=>'Створити віджет',
            'url'=>route('widget.create'),
            'image'=>'add-circle-outline',
            'guard'=>'admin'
       ],[
            'name'=>'Модулі',
            'url'=>route('devices'),
            'image'=>'hardware-chip-outline',
            'guard'=>''
       ],[
            'name'=>'Додати модуль',
            'url'=>route('devices.create'),
            'image'=>'add-circle-outline',
            'guard'=>''
       ],[
            'name'=>'Сценарії',
            'url'=>route('scenario'),
            'image'=>'construct-outline',
            'guard'=>''
       ],[
            'name'=>'Додати сценарій',
            'url'=>route('scenario.create'),
            'image'=>'add-circle-outline',
            'guard'=>''
       ],[
            'name'=>'Голосовий помічник',
            'url'=>route('voice'),
            'image'=>'mic-outline',
            'guard'=>''
       ],[
            'name'=>'Стоврити команду',
            'url'=>route('voice.create'),
            'image'=>'add-circle-outline',
            'guard'=>''
       ],[
            'name'=>'Налаштування',
            'url'=>route('profile'),
            'image'=>'settings-outline',
            'guard'=>''
       ],[
            'name'=>'Вийти',
            'url'=>route('logout'),
            'image'=>'log-out-outline',
            'guard'=>''
       ]
];
function MenuGuard(string $GuardString):bool{
    if(empty($GuardString)) return true;
    $guards = explode('|',$GuardString);
    foreach ($guards as $guard){
        if($guard == \Illuminate\Support\Facades\Auth::user()->role)
            return true;
    }
    return false;
}
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}?v={{ filemtime(public_path('css/menu.css')) }}">

    <title>@yield('title',ucwords(Route::currentRouteName()))</title>
    @yield('styles',"")

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body class="app-shell">

<nav id="navbar" aria-label="Main navigation">
    <div class="sidebar-brand">
        <div class="brand-mark">PA</div>
        <div class="brand-copy">
            <strong>PhpAssistant</strong>
            <small>Control Hub</small>
        </div>
    </div>

    <div class="sidebar-user">
        <ion-icon name="person-circle-outline"></ion-icon>
        <span>{{ Auth::user()->name }}</span>
    </div>

    <ul class="navbar-items flexbox-col">
        @foreach($menuList as $menu)
            @if(MenuGuard($menu['guard']))
                <x-menu-item name="{{$menu['name']}}" href="{{$menu['url']}}" icon="{{$menu['image']}}"></x-menu-item>
            @endif
        @endforeach
    </ul>
</nav>

<main id="main" class="container-fluid">
    <header class="app-topbar">
        <div class="app-topbar-title-wrap">
            <p class="app-kicker">Smart Home Workspace</p>
            <h1 class="app-title">@yield('title',ucwords(Route::currentRouteName()))</h1>
        </div>
        <div class="app-topbar-user">
            <ion-icon name="sparkles-outline"></ion-icon>
            <span>{{ Auth::user()->name }}</span>
        </div>
    </header>

    @if(session('success'))
        <div class="alert alert-success ion-fade-in mb-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
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
