@extends('layouts.menu')
@section('title','Dashboard')

@section('content')
    <div class="container mt-5">
        <div class="row">
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
    </div>
    <script>
        function PostSend(data){
            fetch('{{route('voice.command')}}', {
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

