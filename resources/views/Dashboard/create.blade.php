@extends('layouts.menu')
@section('title','Dashboard Widgets')

@section('content')
    <div class="container mt-5">
        <form id="accessForm" class="mb-4">
            <div class="form-row align-items-center">
                <div class="col-auto">
                    <input id="accessKey" type="text" class="form-control mb-2" placeholder="Введіть ключ безпеки" required>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-primary mb-2" onclick="Click()">Знайти</button>
                </div>
            </div>
        </form>

        <div id="widgetContainer" class="row">
            @foreach($widgets as $widget)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="widget-title">{{ $widget->name }}</h5>
                        </div>
                        <div class="card-body">
                            <p>Ключ доступу: {{ $widget->access_key ?? 'без ключа' }}</p>
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
                            <div class="text-center">
                                <a href="{{ route('dashboard.widget.add',$widget->id) }}" class="btn btn-success">Добавить</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        function Click() {
            const url = document.getElementById('accessKey');
            window.location.replace(`{{ route('widget') }}/${url.value}`);
        }
    </script>
@endsection

