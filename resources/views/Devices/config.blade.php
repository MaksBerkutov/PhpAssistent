@extends('layouts.menu')
@section('title','Arduino Dashboard')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/deactive.css')}}">
@endsection
@section('content')

    <div class="container mt-5">
        <h1>Редактирование данных</h1>

        <form method="post" action="{{route('devices.configure')}}" >
            @csrf
            @php
                $data = json_decode($jsonData, true);
            @endphp

            @foreach ($data as $key => $value)
                <div class="mb-3"  id="dataContainer">
                    <label for="{{ $key }}" class="form-label">{{ ucfirst($key) }}:</label>

                    @if (is_numeric($value) && strpos((string)$value, '.') !== false)  <!-- Число с плавающей запятой -->
                    <input onchange="edit()" type="number" class="form-control" id="{{ $key }}" name="{{ $key }}" value="{{ $value }}" step="any" required>
                    @elseif (is_numeric($value))  <!-- Просто число -->
                    <input onchange="edit()" type="number" class="form-control" id="{{ $key }}" name="{{ $key }}" value="{{ $value }}" required>
                    @elseif (is_string($value))  <!-- Если строка -->
                    <input onchange="edit()" type="text" class="form-control" id="{{ $key }}" name="{{ $key }}" value="{{ $value }}" required>
                    @endif
                </div>
            @endforeach

            <input type="hidden" id="jsonData" name="jsonData">
            <input type="hidden"  name="id" value="{{$id}}">

            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>

    <script>
        function edit(){
            var updatedData = {};
            var inputs = document.querySelectorAll("#dataContainer input");
            inputs.forEach(function(input) {
                updatedData[input.name] = input.value;
            });

            document.getElementById("jsonData").value = JSON.stringify(updatedData);

            var formData = new FormData(document.getElementById("dataForm"));
        }
    </script>

@endsection
