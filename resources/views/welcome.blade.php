@php
    $currentTheme = session('theme', 'light');
    if (!in_array($currentTheme, ['light', 'dark'], true)) {
        $currentTheme = 'light';
    }

    $currentLocale = app()->getLocale();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('ui.welcome.page_title') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f7f4ef;
            --bg-soft: #fffaf4;
            --ink: #1f272d;
            --muted: #5f717d;
            --accent: #d8642c;
            --accent-2: #1f7a72;
            --line: rgba(31, 39, 45, 0.12);
            --shadow: 0 20px 45px rgba(31, 39, 45, 0.12);
            --radius: 20px;
        }

        body[data-theme="dark"] {
            --bg: #0f1316;
            --bg-soft: #171e24;
            --ink: #dbe6ef;
            --muted: #9fb1c0;
            --accent: #e37b49;
            --accent-2: #38a79a;
            --line: rgba(159, 177, 192, 0.22);
            --shadow: 0 22px 50px rgba(0, 0, 0, 0.42);
            color-scheme: dark;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Sora", "Manrope", "Segoe UI", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 14% 16%, rgba(216, 100, 44, 0.2), transparent 35%),
                radial-gradient(circle at 88% 18%, rgba(31, 122, 114, 0.18), transparent 36%),
                linear-gradient(145deg, #fffefb 0%, var(--bg) 100%);
        }

        .shell {
            width: min(1120px, calc(100% - 26px));
            margin: 12px auto;
            border-radius: 26px;
            border: 1px solid var(--line);
            background: color-mix(in srgb, var(--bg-soft) 84%, transparent);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 18px;
            border-bottom: 1px solid var(--line);
            background: color-mix(in srgb, var(--bg-soft) 88%, transparent);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
        }

        .mark {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: linear-gradient(140deg, var(--accent-2), #2f9d81);
            font-size: 0.82rem;
            letter-spacing: 0.04em;
        }

        .top-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .pref-group {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.72rem;
            color: var(--muted);
            font-weight: 700;
            margin-right: 2px;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 30px;
            padding: 0 9px;
            border-radius: 999px;
            border: 1px solid var(--line);
            text-decoration: none;
            color: var(--muted);
            background: color-mix(in srgb, var(--bg-soft) 90%, transparent);
            font-size: 0.72rem;
            font-weight: 700;
        }

        .chip.is-active {
            color: #fff;
            border-color: transparent;
            background: linear-gradient(140deg, var(--accent-2), #2f9d81);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 0 14px;
            border-radius: 11px;
            border: 1px solid transparent;
            text-decoration: none;
            font-size: 0.86rem;
            font-weight: 700;
        }

        .btn-main {
            color: #fff;
            background: linear-gradient(140deg, var(--accent), #b84b1a);
            box-shadow: 0 10px 18px rgba(184, 75, 26, 0.28);
        }

        .btn-ghost {
            color: var(--accent-2);
            background: transparent;
            border-color: rgba(31, 122, 114, 0.35);
        }

        body[data-theme="dark"] .btn-ghost {
            color: #8de5db;
            border-color: rgba(141, 229, 219, 0.34);
        }

        .hero {
            padding: clamp(20px, 4vw, 44px) clamp(16px, 3vw, 40px);
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 16px;
            align-items: stretch;
        }

        .hero-main {
            background: linear-gradient(160deg, rgba(31, 122, 114, 0.1), rgba(216, 100, 44, 0.1));
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: clamp(18px, 2vw, 30px);
        }

        .hero-kicker {
            margin: 0 0 10px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 0.72rem;
            color: var(--muted);
            font-weight: 700;
        }

        h1 {
            margin: 0;
            font-size: clamp(1.6rem, 3vw, 2.4rem);
            line-height: 1.2;
            color: var(--ink);
        }

        .hero-text {
            margin: 12px 0 0;
            max-width: 64ch;
            line-height: 1.7;
            color: var(--muted);
        }

        .hero-points {
            margin: 16px 0 0;
            display: grid;
            gap: 8px;
            color: var(--muted);
            font-size: 0.93rem;
        }

        .hero-card {
            background: var(--bg-soft);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: clamp(16px, 2vw, 24px);
            display: grid;
            align-content: start;
            gap: 10px;
        }

        .hero-card h2 {
            margin: 0;
            font-size: 1.06rem;
            color: var(--ink);
        }

        .hero-card p {
            margin: 0;
            color: var(--muted);
            line-height: 1.6;
            font-size: 0.9rem;
        }

        .features {
            padding: 0 clamp(16px, 3vw, 40px) clamp(20px, 3vw, 32px);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
        }

        .feature {
            border-radius: 16px;
            border: 1px solid var(--line);
            background: color-mix(in srgb, var(--bg-soft) 92%, transparent);
            padding: 14px;
        }

        .feature strong {
            display: block;
            margin-bottom: 8px;
            font-size: 0.95rem;
            color: var(--ink);
        }

        .feature p {
            margin: 0;
            color: var(--muted);
            font-size: 0.88rem;
            line-height: 1.6;
        }

        @media (max-width: 900px) {
            .hero { grid-template-columns: 1fr; }
        }

        @media (max-width: 560px) {
            .shell {
                width: calc(100% - 12px);
                margin: 6px auto;
                border-radius: 18px;
            }

            .topbar { padding: 10px 12px; }
            .top-actions { gap: 6px; }

            .btn {
                min-height: 36px;
                padding: 0 10px;
                font-size: 0.8rem;
            }

            .chip {
                min-height: 28px;
                padding: 0 7px;
                font-size: 0.68rem;
            }
        }
    </style>
</head>
<body data-theme="{{ $currentTheme }}">
    <div class="shell">
        <header class="topbar">
            <div class="brand">
                <span class="mark">PA</span>
                <span>{{ __('ui.layout.brand') }}</span>
            </div>

            <div class="top-actions">
                <div class="pref-group">
                    <span>{{ __('ui.common.language') }}</span>
                    <a href="{{ route('preferences.locale', 'ru') }}" class="chip @if($currentLocale === 'ru') is-active @endif">RU</a>
                    <a href="{{ route('preferences.locale', 'en') }}" class="chip @if($currentLocale === 'en') is-active @endif">EN</a>
                </div>

                <div class="pref-group">
                    <span>{{ __('ui.common.theme') }}</span>
                    <a href="{{ route('preferences.theme', 'light') }}" class="chip @if($currentTheme === 'light') is-active @endif">{{ __('ui.common.light') }}</a>
                    <a href="{{ route('preferences.theme', 'dark') }}" class="chip @if($currentTheme === 'dark') is-active @endif">{{ __('ui.common.dark') }}</a>
                </div>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('home') }}" class="btn btn-main">{{ __('ui.common.open_workspace') }}</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-main">{{ __('ui.common.login') }}</a>
                        <a href="{{ route('login') }}" class="btn btn-ghost">{{ __('ui.common.create_account') }}</a>
                    @endauth
                @endif
            </div>
        </header>

        <section class="hero">
            <article class="hero-main">
                <p class="hero-kicker">{{ __('ui.welcome.kicker') }}</p>
                <h1>{{ __('ui.welcome.title') }}</h1>
                <p class="hero-text">{{ __('ui.welcome.description') }}</p>
                <div class="hero-points">
                    <span>{{ __('ui.welcome.point_1') }}</span>
                    <span>{{ __('ui.welcome.point_2') }}</span>
                    <span>{{ __('ui.welcome.point_3') }}</span>
                    <span>{{ __('ui.welcome.point_4') }}</span>
                </div>
            </article>

            <aside class="hero-card">
                <h2>{{ __('ui.welcome.quick_start') }}</h2>
                <p>{{ __('ui.welcome.step_1') }}</p>
                <p>{{ __('ui.welcome.step_2') }}</p>
                <p>{{ __('ui.welcome.step_3') }}</p>
                <p>{{ __('ui.welcome.step_4') }}</p>
                <p>{{ __('ui.welcome.step_5') }}</p>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-main" style="margin-top: 4px;">{{ __('ui.common.go_to_dashboard') }}</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-main" style="margin-top: 4px;">{{ __('ui.common.start_now') }}</a>
                @endauth
            </aside>
        </section>

        <section class="features">
            <article class="feature">
                <strong>{{ __('ui.welcome.features_widgets_title') }}</strong>
                <p>{{ __('ui.welcome.features_widgets_text') }}</p>
            </article>
            <article class="feature">
                <strong>{{ __('ui.welcome.features_scenario_title') }}</strong>
                <p>{{ __('ui.welcome.features_scenario_text') }}</p>
            </article>
            <article class="feature">
                <strong>{{ __('ui.welcome.features_roles_title') }}</strong>
                <p>{{ __('ui.welcome.features_roles_text') }}</p>
            </article>
            <article class="feature">
                <strong>{{ __('ui.welcome.features_voice_title') }}</strong>
                <p>{{ __('ui.welcome.features_voice_text') }}</p>
            </article>
        </section>
    </div>
</body>
</html>
