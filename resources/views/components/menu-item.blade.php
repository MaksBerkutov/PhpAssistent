@php
    $currentUrl = url()->current();
    $normalizedHref = rtrim($href, '/');
    $isActive = $currentUrl === $href || ($normalizedHref !== '' && \Illuminate\Support\Str::startsWith($currentUrl, $normalizedHref . '/'));
@endphp

<li class="navbar-item flexbox-left {{ $isActive ? 'is-active' : '' }}">
    <a class="navbar-item-inner flexbox-left" href="{{$href}}" @if($isActive) aria-current="page" @endif>
        <div class="navbar-item-inner-icon-wrapper flexbox">
            <ion-icon name="{{$icon}}"></ion-icon>
        </div>
        <span class="link-text">{{$name}}</span>
    </a>
</li>
