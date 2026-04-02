@extends('layouts.menu')
@section('title', 'Голосовой помощник')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/voice.css') }}">
    <script src="{{ asset('js/voice.js') }}"></script>
@endsection

@section('content')
    <div class="page-shell">
        <section class="page-head">
            <div>
                <h2 class="page-title">Голосовой помощник</h2>
                <p class="page-subtitle">Система слушает ключевое слово и выполняет сохранённые голосовые команды.</p>
            </div>
            <a href="{{ route('voice.create') }}" class="btn btn-outline-primary">Добавить команду</a>
        </section>

        <section class="page-card text-center">
            <div class="animation" id="animation">
                <div class="circle" style="animation-play-state: paused; display: none;"></div>
                <div class="circle" style="animation-play-state: paused; display: none;"></div>
                <div class="circle" style="animation-play-state: paused; display: none;"></div>
            </div>
            <div class="status" id="status">Статус: ожидание команды "Ассистент"</div>
        </section>
    </div>

    <script>
        document.addEventListener('changeState', function (event) {
            const circles = document.querySelectorAll('.circle');
            const status = document.getElementById('status');

            if (!status) {
                return;
            }

            if (event.detail.status) {
                circles.forEach(circle => {
                    circle.style.animationPlayState = 'running';
                    circle.style.display = 'block';
                });
                status.textContent = 'Статус: слушаю команду...';
            } else {
                circles.forEach(circle => {
                    circle.style.animationPlayState = 'paused';
                    circle.style.display = 'none';
                });
                status.textContent = 'Статус: ожидание команды "Ассистент"';
            }
        });

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

        function handleCommand(command) {
            switch (command.toLowerCase()) {
                @foreach($commands as $command)
                case '{{ $command->text_trigger }}'.toLowerCase():
                    PostSend({
                        devices_id: '{{ $command->devices_id }}',
                        command: '{{ $command->command }}'
                    });
                    @if(optional($command->voice))
                    speak('{{ $command->voice }}');
                    @endif
                    break;
                @endforeach
                default:
                    speak('Неизвестная команда');
            }
        }
    </script>
@endsection
