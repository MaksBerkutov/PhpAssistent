@extends('layouts.menu')
@section('title', 'Панель управления')

@section('content')
    <div class="page-shell">
        <section class="page-head">
            <div>
                <h2 class="page-title">Панель управления</h2>
                <p class="page-subtitle">Здесь отображаются добавленные виджеты и быстрые элементы контроля устройств.</p>
            </div>
            <a href="{{ route('dashboard.widget') }}" class="btn btn-primary">Добавить виджет</a>
        </section>

        @if ($widgets->isEmpty())
            <section class="page-empty">
                <p class="mb-2">На панели пока нет виджетов.</p>
                <a href="{{ route('dashboard.widget') }}" class="btn btn-outline-primary">Выбрать виджет</a>
            </section>
        @else
            <section class="container-fluid px-0">
                <div class="row g-3">
                    @foreach($widgets as $widget)
                        <x-dynamic-component :component="$widget->widget->widget_name"
                                             :device_url="$widget->device->url"
                                             :available="$widget->device->available"
                                             :device_id="$widget->device_id"
                                             :command="$widget->command"
                                             :key="$widget->key"
                                             :argument="$widget->argument"
                                             :name="$widget->name"
                                             :id="$widget->id"
                                             :data="json_decode($widget->values)">
                        </x-dynamic-component>
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    <script>
        function PostSend(data) {
            fetch('{{ route('voice.command') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(result => {
                    console.log('Success:', result);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
@endsection
