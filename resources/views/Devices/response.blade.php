@extends('layouts.menu')
@section('title','Arduino Response')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4 text-center">{{$validated["command"]}}</h1>

        <div class="row">
            @foreach($response as $key => $value)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Ключ: <span class="text-primary">{{ $key }}</span></h5>
                            <p class="card-text">
                            @if(is_array($value) || is_object($value))
                                <ul>
                                    @foreach($value as $subKey => $subValue)
                                        <li><strong>{{ $subKey }}:</strong> {{ $subValue }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-success">{{ $value }}</span>
                                @endif
                                </p>
                        </div>
                        <div class="card-footer text-center">
                            <small class="text-muted">{{$validated["url"]}}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
