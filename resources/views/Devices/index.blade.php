@extends('layouts.menu')
@section('title','Arduino Dashboard')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/deactive.css')}}">
@endsection
@section('content')

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                @foreach($devices as $device)
                    @php
                        $commands = json_decode($device->command);
                        $flag = true;
                    @endphp
                    <div class="card text-center @if(!($device->available)) deactivated @endif">
                        <div class="card-header bg-primary text-white">
                            <h2 class="card-title">{{$device->name}}</h2>
                            <x-online-status status="{{$device->available}}"/>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <form method="post" action="{{route('devices.send')}}">
                                    @csrf
                                    <input type="hidden" name="url" value="{{$device->url}}">
                                    @foreach($commands as $command)
                                        @if(str_ends_with($command,"_REC"))
                                            <button type="submit" name="command" value="{{$command}}" class="btn btn-outline-success">{{$command}}</button>
                                        @elseif($flag)
                                            <button type="submit"  name="command" value="{{$command}}" class="btn btn-outline-primary">{{$command}}</button>
                                        @else
                                            <button type="submit"  name="command" value="{{$command}}" class="btn btn-outline-secondary">{{$command}}</button>
                                        @endif
                                        @php
                                            $flag=!$flag;
                                        @endphp

                                    @endforeach
                                </form>
                                @if($device->ota)
                                <form method="post" action="{{route('devices.firmware')}}" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$device->id}}">
                                    <input type="file" name="firmware" accept=".bin" class="form-control my-3">
                                    <button type="submit" class="btn btn-outline-warning">Обновить</button>
                                    @error('id')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    @error('firmware')
                                    <div class="text-danger">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </form>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer text-muted">
                            {{$device->name_board}} Command Center
                        </div>
                    </div>


                @endforeach

            </div>
        </div>
    </div>

@endsection
