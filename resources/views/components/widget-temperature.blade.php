<link rel="stylesheet" href="{{ asset('css/deactive.css') }}">

<div class="col-md-6 @if(!$isOnline) deactivated @endif">
    <div class="card rounded-3 h-100">
        <div class="card-header rounded-top d-flex justify-content-between align-items-center">
            <span>{{ $name }}</span>
            <x-online-status status="{{ $isOnline }}"/>
        </div>

        <div class="card-body d-flex flex-wrap justify-content-around align-items-center gap-3">
            <div class="text-center">
                <ion-icon name="thermometer-outline" style="font-size: 50px;"></ion-icon>
                <h5 class="card-title mt-3">{{ __('ui.widgets.temperature') }}</h5>
                <p class="card-text mb-0">{{ $temperature }}°C</p>
            </div>

            <div class="text-center">
                <ion-icon name="water-outline" style="font-size: 50px;"></ion-icon>
                <h5 class="card-title mt-3">{{ __('ui.widgets.humidity') }}</h5>
                <p class="card-text mb-0">{{ $humidity }}%</p>
            </div>
        </div>
    </div>
</div>
