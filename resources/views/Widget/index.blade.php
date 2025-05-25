@extends('layouts.menu')
@section('title','All Widgets')

@section('content')
    <div class="container">
        <h1 class="mb-4">Віджети</h1>

        <div class="row">
            @foreach($widgets as $widget)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            {{$widget->name}}
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Ім'я компонента: {{$widget->widget_name}}</h5>
                            <p class="card-text">Ключ: {{ $widget->accesses_key }}</p>
                            <h6>Параметри:</h6>
                            <div class="row">
                                @php
                                    $commands = json_decode($widget->input_params,true );
                                @endphp
                                @foreach($commands as $key => $value)
                                    <div class="col-12 param-card mb-2">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $key }}</h6>
                                                <p class="card-text">Тип: {{ $value }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('widget.edit', $widget->id) }}" class="btn btn-primary">Редагувати</a>
                            <form action="{{route('widget.delete', $widget->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Видалити</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

