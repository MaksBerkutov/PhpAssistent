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
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/menu.css')}}">

    <title>@yield('title',ucwords(Route::currentRouteName()))</title>
    @yield('styles',"")

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<style>
    .alertRotate{
        display: none;
    }
    @media (orientation: portrait)and (max-width: 600px) {
        #main , #navbar{
            display: none;
        }


        .alertRotate{
            display: block;
            width: 100vw;
            height: 100vh;
            background: black;
            color: white;
            text-align: center;

        }
    }
</style>

<body>

<nav id="navbar" style="z-index: 1">
    <ul class="navbar-items flexbox-col">
        <li class="navbar-logo navbar-item flexbox-left" >
            <a class="navbar-item-inner flexbox">
                <svg fill="#000000" height="800px" width="800px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     viewBox="0 0 485.18 485.18" xml:space="preserve">
<g>
    <g>
        <path d="M483.94,338.09l-15.4-33.2c-2.2-4.8-7-7.8-12.2-7.8h-73.4v-19.6h36.1c7.4,0,13.5-6,13.5-13.5c0-7.4-6-13.5-13.5-13.5
			h-36.1v-21h73.4c5.3,0,10-3.1,12.2-7.8l15.3-33.3c3.1-6.8,0.2-14.8-6.6-17.9c-6.8-3.1-14.8-0.1-17.9,6.6l-11.7,25.5h-64.7v-22.3
			h36.1c7.4,0,13.5-6,13.5-13.5c0-7.4-6-13.5-13.5-13.5h-36.1v-12.1c0-21.4-17.4-38.8-38.8-38.8h-14.4v-36.3c0-7.4-6-13.5-13.5-13.5
			s-13.5,6-13.5,13.5v36.3h-22.3v-64.9l25.4-11.7c6.8-3.1,9.7-11.1,6.6-17.9c-3.1-6.8-11.1-9.7-17.9-6.6l-33.2,15.3
			c-4.8,2.2-7.8,7-7.8,12.2v73.5h-21v-36.2c0-7.4-6-13.5-13.5-13.5c-7.4,0-13.5,6-13.5,13.5v36.3h-19.6v-73.5c0-5.3-3.1-10-7.8-12.2
			l-33.3-15.3c-6.8-3.1-14.8-0.1-17.9,6.6c-3.1,6.8-0.2,14.8,6.6,17.9l25.4,11.7v64.9h-17.7c-19.6,0-38.1,15.5-39.1,27.8
			c0,0.3,0,0.7,0,1v22.1h-36.1c-7.4,0-13.5,6-13.5,13.5c0,7.4,6,13.5,13.5,13.5h36.1v22.2h-64.7l-11.7-25.4
			c-3.1-6.8-11.1-9.7-17.9-6.6s-9.7,11.1-6.6,17.9l15.3,33.2c2.2,4.8,7,7.8,12.2,7.8h73.4v21h-36.1c-7.4,0-13.5,6-13.5,13.5
			c0,7.4,6,13.5,13.5,13.5h36.1v19.6h-73.3c-5.3,0-10,3.1-12.2,7.8l-15.3,33.3c-3.1,6.8-0.2,14.8,6.6,17.9
			c6.8,3.1,14.8,0.1,17.9-6.6l11.7-25.4h64.7v10.2c0.5,35.9,21.4,48.6,39.1,48.6h17.7v64.9l-25.5,11.6c-6.8,3.1-9.7,11.1-6.6,17.9
			c3.1,6.8,11.1,9.7,17.9,6.6l33.3-15.3c4.8-2.2,7.8-7,7.8-12.2v-73.5h19.6v36.3c0,7.4,6,13.5,13.5,13.5c7.4,0,13.5-6,13.5-13.5
			v-36.3h21v73.5c0,5.3,3.1,10,7.8,12.2l33.3,15.3c6.8,3.1,14.8,0.1,17.9-6.6c3.1-6.8,0.2-14.8-6.6-17.9l-25.4-11.7v-64.9h22.3v36.3
			c0,7.4,6,13.5,13.5,13.5s13.5-6,13.5-13.5v-36.3h14.4c21.4,0,38.8-17.4,38.8-38.8v-20h64.7l11.7,25.4c3.1,6.8,11.1,9.7,17.9,6.6
			C484.14,352.89,487.04,344.89,483.94,338.09z M356.04,343.99h-0.1c0,6.5-5.3,11.9-11.9,11.9h-202.7c-10.7,0-12.1-15.4-12.2-21.9
			v-199.3c2.1-2,6.8-5.4,12.2-5.4h202.8c6.5,0,11.9,5.3,11.9,11.9V343.99z"/>
    </g>
</g>
</svg>

            </a>
            <span class="link-text" style="color: white">  {{Auth::user()->name}}</span>
        </li>
        @foreach($menuList as $menu)
            @if(MenuGuard($menu['guard']))
                <x-menu-item name="{{$menu['name']}}" href="{{$menu['url']}}" icon="{{$menu['image']}}"></x-menu-item>
            @endif
        @endforeach

    </ul>
</nav>

<main id="main" class="flexbox-col container " style="z-index: -1;">
    @if(session('success'))
        <div class="alert alert-success ion-fade-in">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger ion-fade-in">
            {{ session('error') }}
        </div>
    @endif
    @yield('content')
</main>
<div class="alertRotate">
    <iframe src="https://giphy.com/embed/PfY4nKis93ZHXMywps" width="270" height="480" style="" frameBorder="0" class="giphy-embed" allowFullScreen></iframe>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

