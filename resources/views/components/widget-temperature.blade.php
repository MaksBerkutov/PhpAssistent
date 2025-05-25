<link rel="stylesheet" href="{{asset('css/deactive.css')}}">

<div class="col-md-6 @if(!$isOnline) deactivated @endif" >
    <div class="card rounded-3">
        <div class="card-header rounded-top">
             {{$name}}
            <x-online-status status="{{$isOnline}}"/>

        </div>
        <div class="card-body d-flex justify-content-between align-items-center">
            <div class="temperature-section text-center">
                <ion-icon name="thermometer-outline" style="font-size: 50px;"></ion-icon>
                <h5 class="card-title mt-3">Температура</h5>
                <p class="card-text">{{$temperature}}°C</p>
            </div>

            <div class="humidity-section text-center">
                <ion-icon name="thunderstorm-outline" style="font-size: 50px;"></ion-icon>
                <h5 class="card-title mt-3">Вологість</h5>
                <p class="card-text">{{$humidity}}%</p>
            </div>
        </div>
    </div>
</div>
