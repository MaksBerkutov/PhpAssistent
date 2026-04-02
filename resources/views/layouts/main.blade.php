@php
    $currentTheme = session('theme', 'light');
    if (!in_array($currentTheme, ['light', 'dark'], true)) {
        $currentTheme = 'light';
    }

    $currentLocale = app()->getLocale();
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <title>@yield('title', __('ui.auth.page_title'))</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    @yield('styles',"")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
        :root {
            --public-bar-bg: rgba(255, 255, 255, 0.86);
            --public-bar-line: rgba(31, 39, 45, 0.16);
            --public-bar-text: #41515e;
            --public-chip-bg: rgba(255, 255, 255, 0.9);
            --public-chip-active-bg: #1f7a72;
            --public-chip-active-text: #ffffff;
        }

        body.public-shell[data-theme="dark"] {
            --public-bar-bg: rgba(14, 19, 24, 0.88);
            --public-bar-line: rgba(139, 156, 170, 0.3);
            --public-bar-text: #c8d5df;
            --public-chip-bg: rgba(31, 40, 48, 0.92);
            --public-chip-active-bg: #d8642c;
            --public-chip-active-text: #fff7f2;
            color-scheme: dark;
        }

        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
        }

        body.public-shell {
            min-height: 100vh;
            min-height: 100dvh;
            padding-top: 58px;
        }

        .public-prefbar {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 1200;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 8px;
            border-radius: 12px;
            border: 1px solid var(--public-bar-line);
            background: var(--public-bar-bg);
            backdrop-filter: blur(8px);
            box-shadow: 0 10px 22px rgba(31, 39, 45, 0.12);
            font-family: "Sora", "Manrope", sans-serif;
        }

        .public-prefgroup {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: var(--public-bar-text);
            font-size: 0.73rem;
            font-weight: 700;
        }

        .public-prefchip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 30px;
            padding: 0 10px;
            border-radius: 999px;
            border: 1px solid var(--public-bar-line);
            background: var(--public-chip-bg);
            color: var(--public-bar-text);
            text-decoration: none;
            font-size: 0.72rem;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .public-prefchip:hover,
        .public-prefchip:focus {
            transform: translateY(-1px);
            color: var(--public-bar-text);
        }

        .public-prefchip.is-active {
            border-color: transparent;
            background: var(--public-chip-active-bg);
            color: var(--public-chip-active-text);
            box-shadow: 0 8px 18px rgba(31, 39, 45, 0.2);
        }

        @media (max-width: 560px) {
            body.public-shell {
                padding-top: 86px;
            }

            .public-prefbar {
                top: 8px;
                left: 8px;
                right: 8px;
                justify-content: space-between;
                flex-wrap: wrap;
                align-items: flex-start;
            }

            .public-prefgroup {
                font-size: 0.69rem;
                flex-wrap: wrap;
            }

            .public-prefchip {
                min-height: 28px;
                padding: 0 8px;
                font-size: 0.68rem;
            }
        }
    </style>
</head>
<body class="public-shell" data-theme="{{ $currentTheme }}">
<div class="public-prefbar">
    <div class="public-prefgroup">
        <span>{{ __('ui.common.language') }}</span>
        <a href="{{ route('preferences.locale', 'ru') }}" class="public-prefchip @if($currentLocale === 'ru') is-active @endif">RU</a>
        <a href="{{ route('preferences.locale', 'en') }}" class="public-prefchip @if($currentLocale === 'en') is-active @endif">EN</a>
    </div>
    <div class="public-prefgroup">
        <span>{{ __('ui.common.theme') }}</span>
        <a href="{{ route('preferences.theme', 'light') }}" class="public-prefchip @if($currentTheme === 'light') is-active @endif">{{ __('ui.common.light') }}</a>
        <a href="{{ route('preferences.theme', 'dark') }}" class="public-prefchip @if($currentTheme === 'dark') is-active @endif">{{ __('ui.common.dark') }}</a>
    </div>
</div>

@yield('content')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
