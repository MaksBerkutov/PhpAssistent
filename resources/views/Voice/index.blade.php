@extends('layouts.menu')
@section('title','Arduino Voice')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/voice.css')}}">

    <script src="{{asset('js/voice.js')}}"></script>
@endsection
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div >
        <div class="animation" id="animation">
            <div class="circle" style="animation-play-state: paused; display: none;"></div>
            <div class="circle" style="animation-play-state: paused; display: none;"></div>
            <div class="circle" style="animation-play-state: paused; display: none;"></div>
        </div>
        <div class="status" id="status">Статус: очікування команди "Ассистент"</div>
    </div>

    <script>

        document.addEventListener('changeState', function(event) {
            console.log(event.detail.status)
            const circles = document.querySelectorAll('.circle');
            const status = document.getElementById('status');

            if (event.detail.status) {
                // Запуск анимации
                circles.forEach(circle => {
                    circle.style.animationPlayState = 'running';
                    circle.style.display = 'block';
                });
                status.textContent = 'Статус: чекаю на команду...';
            } else {
                // Остановка анимации
                circles.forEach(circle => {
                    circle.style.animationPlayState = 'paused';
                    circle.style.display = 'none';
                });
                status.textContent = 'Статус: очікування команди "Ассистент"';
            }
        });

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
        function handleCommand(command) {
            switch (command.toLowerCase()) {
                @foreach($commands as $command)
                case '{{ $command->text_trigger }}'.toLowerCase():
                    PostSend({
                        devices_id:"{{$command->devices_id}}",
                        command:"{{$command->command}}"
                    })
                    @if(optional($command->voice))
                    speak("{{$command->voice}}")
                    @endif

                    break;
                @endforeach
                default:
                    speak('Невідома команда');
            }
        }
    </script>

@endsection

